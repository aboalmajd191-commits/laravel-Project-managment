<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\IndividualSupportSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IndividualSupportSessionController extends Controller
{
    public function index(): View
    {
        $sessions = IndividualSupportSession::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.individual_support_session.index', compact('sessions'));
    }

    public function create(): View
    {
        return view('manager.services.individual_support_session.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funder'              => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'status'              => 'required|in:draft,active,completed',
            'project_id'          => 'nullable|exists:projects,id',
            'sector_type'         => 'required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ]);

        $data['submitted_by'] = auth()->id();
        $data['approval_status'] = 'approved';
        $data['parent_id'] = null;

        IndividualSupportSession::create($data);

        return redirect()->route('project_manager.services.individual_support_session.index')
            ->with('success', 'تم إنشاء سجل جلسات الدعم الفردي بنجاح.');
    }

    public function show(IndividualSupportSession $individual_support_session): View
    {
        $individual_support_session->load(['submitter', 'children.submitter', 'children.details']);
        return view('manager.services.individual_support_session.show', compact('individual_support_session'));
    }

    public function edit(IndividualSupportSession $individual_support_session): View
    {
        $individual_support_session->load('children.submitter');
        return view('manager.services.individual_support_session.edit', compact('individual_support_session'));
    }

    public function update(Request $request, IndividualSupportSession $individual_support_session): RedirectResponse
    {
        if ($request->has('tab') && $request->tab === 'entry') {
            $data = $request->validate([
                'specialist'      => 'nullable|string|max:255',
                'case_code'       => 'nullable|string|max:50',
                'age'             => 'nullable|integer',
                'mobile'          => 'nullable|string|max:20',
                'address'         => 'nullable|string|max:255',
                'education_level' => 'nullable|string|max:100',
                'marital_status'  => 'nullable|string|max:50',
                'main_complaint'  => 'nullable|string',
                'sessions'        => 'nullable|array',
                'sessions.*.session_number'       => 'nullable|string',
                'sessions.*.session_date'         => 'nullable|date',
                'sessions.*.session_time'         => 'nullable|string',
                'sessions.*.intervention_file'    => 'nullable|string',
                'sessions.*.intervention_summary' => 'nullable|string',
            ]);

            DB::transaction(function () use ($request, $individual_support_session, $data) {
                $child = $individual_support_session->replicate();
                $child->parent_id      = $individual_support_session->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill(Arr::except($data, 'sessions'));
                $child->save();

                if (!empty($data['sessions'])) {
                    foreach ($data['sessions'] as $session) {
                        $child->details()->create([
                            'session_number'       => $session['session_number'] ?? null,
                            'session_date'         => $session['session_date'] ?? null,
                            'session_time'         => $session['session_time'] ?? null,
                            'intervention_file'    => $session['intervention_file'] ?? null,
                            'intervention_summary' => $session['intervention_summary'] ?? null,
                        ]);
                    }
                }

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.individual_support_session.edit', [$individual_support_session, 'tab' => 'entry'])
                ->with('success', 'تم إضافة بيانات الجلسة بنجاح.');
        }

        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funder'              => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'status'              => 'required|in:draft,active,completed',
            'project_id'          => 'nullable|exists:projects,id',
            'sector_type'         => 'required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ]);

        $individual_support_session->update($data);

        return redirect()
            ->route('project_manager.services.individual_support_session.edit', $individual_support_session)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(IndividualSupportSession $individual_support_session): RedirectResponse
    {
        $individual_support_session->delete();
        return redirect()->route('project_manager.services.individual_support_session.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}

