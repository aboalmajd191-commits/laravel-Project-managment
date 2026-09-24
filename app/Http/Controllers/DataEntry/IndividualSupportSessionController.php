<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\IndividualSupportSessionRequest;
use App\Models\IndividualSupportSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndividualSupportSessionController extends Controller
{
    /**
     * Shows the list of approved parent sessions available for entry,
     * and also the child entries the user already submitted.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Parent projects available for new data entry
        $parentSessions = IndividualSupportSession::whereNull('parent_id')
            ->where('status', 'active')
            ->withCount(['children as my_entries_count' => function ($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }])
            ->latest()
            ->paginate(10, ['*'], 'parents_page');

        // Child entries submitted by this user (with their approval status)
        $mySubmissions = IndividualSupportSession::whereNotNull('parent_id')
            ->where('submitted_by', $user->id)
            ->with('parent')
            ->latest()
            ->paginate(10, ['*'], 'submissions_page');

        return view('entry.services.individual_support_session.index', compact('parentSessions', 'mySubmissions'));
    }

    /**
     * Edit an existing child entry (only if not approved yet)
     * OR show the form to create a new child entry from a parent.
     */
    public function edit(IndividualSupportSession $individual_support_session): View
    {
        // If it's a child record
        if ($individual_support_session->parent_id !== null) {
            if ($individual_support_session->approval_status === 'approved') {
                abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            // Check ownership
            if ($individual_support_session->submitted_by !== auth()->id()) {
                abort(403, 'لا يمكنك تعديل بيانات شخص آخر.');
            }
            $individual_support_session->load('details');
            return view('entry.services.individual_support_session.edit', compact('individual_support_session'));
        }

        // It's a parent – show a fresh form to add a new child
        if ($individual_support_session->status !== 'active') {
            abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }

        // Create an empty shell to pass to the view (no DB save yet)
        $emptySession = new IndividualSupportSession([
            'parent_id'    => $individual_support_session->id,
            'project_name' => $individual_support_session->project_name,
            'funder'       => $individual_support_session->funder,
            'start_date'   => $individual_support_session->start_date,
            'end_date'     => $individual_support_session->end_date,
        ]);
        $emptySession->setRelation('details', collect());

        // We still pass the parent for context but the $individual_support_session in the view
        // will be the parent – the form will POST to create a new child.
        $parent = $individual_support_session;

        return view('entry.services.individual_support_session.create_child', compact('parent'));
    }

    public function update(IndividualSupportSessionRequest $request, IndividualSupportSession $individual_support_session): RedirectResponse
    {
        // ... (previous checks)
        if ($individual_support_session->parent_id === null) {
            return back()->with('error', 'لا يمكن تعديل سجل المشروع الأصلي.');
        }

        if ($individual_support_session->approval_status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }

        if ($individual_support_session->submitted_by !== auth()->id()) {
            return back()->with('error', 'لا يمكنك تعديل بيانات شخص آخر.');
        }

        $data = $request->validated();

        \DB::transaction(function () use ($individual_support_session, $data, $request) {
            $childData = \Illuminate\Support\Arr::except($data, ['sessions', 'attachments']);
            $childData['approval_status'] = 'draft';
            $childData['rejection_reason'] = null;
            $individual_support_session->update($childData);
            $individual_support_session->details()->delete();

            foreach ($data['sessions'] ?? [] as $session) {
                $individual_support_session->details()->create([
                    'session_number'       => $session['session_number'] ?? null,
                    'session_date'         => $session['session_date'] ?? null,
                    'session_time'         => $session['session_time'] ?? null,
                    'intervention_file'    => $session['intervention_file'] ?? null,
                    'intervention_summary' => $session['intervention_summary'] ?? null,
                ]);
            }

            $individual_support_session->handleAttachments($request);
        });

        return redirect()->route('data_entry.services.individual_support_session.index')
            ->with('success', 'تم تحديث بيانات الجلسة بنجاح.');
    }

    /**
     * Store a brand new child entry from a parent session.
     */
    public function storeChild(IndividualSupportSessionRequest $request, IndividualSupportSession $individual_support_session): RedirectResponse
    {
        if ($individual_support_session->parent_id !== null) {
            abort(422, 'يجب أن يكون السجل المرجعي أصل وليس فرع.');
        }

        if ($individual_support_session->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }

        $data = $request->validated();

        \DB::transaction(function () use ($individual_support_session, $data, $request) {
            $childData = \Illuminate\Support\Arr::except($data, ['sessions', 'attachments']);
            $childData['parent_id']       = $individual_support_session->id;
            $childData['approval_status'] = 'draft';
            $childData['submitted_by']    = auth()->id();
            // Inherit parent fields
            $childData['project_name'] = $individual_support_session->project_name;
            $childData['funder']       = $individual_support_session->funder;
            $childData['start_date']   = $individual_support_session->start_date;
            $childData['end_date']     = $individual_support_session->end_date;
            $childData['status']       = $individual_support_session->status;
            $childData['sector_type']  = $individual_support_session->sector_type;
            $childData['project_description'] = $individual_support_session->project_description;

            $child = IndividualSupportSession::create($childData);

            foreach ($data['sessions'] ?? [] as $session) {
                $child->details()->create([
                    'session_number'       => $session['session_number'] ?? null,
                    'session_date'         => $session['session_date'] ?? null,
                    'session_time'         => $session['session_time'] ?? null,
                    'intervention_file'    => $session['intervention_file'] ?? null,
                    'intervention_summary' => $session['intervention_summary'] ?? null,
                ]);
            }

            $child->handleAttachments($request);
        });

        return redirect()->route('data_entry.services.individual_support_session.index')
            ->with('success', 'تم حفظ بيانات الجلسة وإرسالها للمراجعة.');
    }

    public function submitAllDrafts(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $updatedCount = IndividualSupportSession::whereNotNull('parent_id')
            ->where('submitted_by', $user->id)
            ->where('approval_status', 'draft')
            ->update(['approval_status' => 'pending']);

        if ($updatedCount > 0) {
            return back()->with('success', "تم إرسال {$updatedCount} جلسة للموافقة بنجاح.");
        }

        return back()->with('info', 'لا توجد جلسات مسودة لإرسالها.');
    }
}
