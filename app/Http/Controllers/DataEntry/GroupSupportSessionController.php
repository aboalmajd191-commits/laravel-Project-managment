<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\GroupSupportSessionRequest;
use App\Models\GroupSupportSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupSupportSessionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = GroupSupportSession::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $sessions = $query->paginate(10);
        return view('entry.services.group_support_session.index', compact('sessions'));
    }

    public function edit(GroupSupportSession $group_support_session): View
    {
        if ($group_support_session->parent_id !== null && $group_support_session->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($group_support_session->parent_id === null && $group_support_session->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        $group_support_session->load('attendees');
        return view('entry.services.group_support_session.edit', compact('group_support_session'));
    }

    public function update(GroupSupportSessionRequest $request, GroupSupportSession $group_support_session): RedirectResponse
    {
        $validatedData = $request->validated();
        $attendees = $validatedData['attendees'];
        $attachments = $request->file('attachments');
        unset($validatedData['attendees'], $validatedData['attachments']);

        if ($group_support_session->parent_id !== null) {
            if ($group_support_session->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            \DB::transaction(function() use ($group_support_session, $validatedData, $attendees, $attachments) {
                $validatedData['approval_status'] = 'pending';
                $validatedData['rejection_reason'] = null;
                $group_support_session->update($validatedData);
                
                $group_support_session->attendees()->delete();
                foreach ($attendees as $att) {
                    $group_support_session->attendees()->create($att);
                }

                $group_support_session->handleAttachments(request());
            });
            return redirect()->route('data_entry.services.group_support_session.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }

        if ($group_support_session->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }

        $childData = array_merge($validatedData, [
            'parent_id' => $group_support_session->id,
            'approval_status' => 'pending',
            'submitted_by' => auth()->id(),
            'project_name' => $group_support_session->project_name,
            'funder' => $group_support_session->funder,
            'start_date' => $group_support_session->start_date,
            'end_date' => $group_support_session->end_date,
            'sector_type' => $group_support_session->sector_type,
            'project_description' => $group_support_session->project_description,
        ]);

        \DB::transaction(function() use ($childData, $attendees, $attachments) {
            $child = GroupSupportSession::create($childData);
            foreach ($attendees as $att) {
                $child->attendees()->create($att);
            }
            $child->handleAttachments(request());
        });

        return redirect()->route('data_entry.services.group_support_session.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
