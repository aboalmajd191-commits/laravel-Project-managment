<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\GroupSupportSession;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class GroupSupportSessionController extends Controller
{
    public function index(): View
    {
        $sessions = GroupSupportSession::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.group_support_session.index', compact('sessions'));
    }

    public function create(): View
    {
        return view('manager.services.group_support_session.create');
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

        GroupSupportSession::create($data);

        return redirect()->route('project_manager.services.group_support_session.index')
            ->with('success', 'تم إنشاء سجل جلسات الدعم الجماعي بنجاح.');
    }

    public function show(GroupSupportSession $group_support_session): View
    {
        $group_support_session->load(['submitter', 'children.submitter', 'children.attendees']);
        return view('manager.services.group_support_session.show', compact('group_support_session'));
    }

    public function edit(GroupSupportSession $group_support_session): View
    {
        $group_support_session->load('children.submitter');
        return view('manager.services.group_support_session.edit', compact('group_support_session'));
    }

    public function update(Request $request, GroupSupportSession $group_support_session): RedirectResponse
    {
        if ($request->tab === 'entry') {
            $data = $request->validate([
                'session_name'        => 'nullable|string|max:255',
                'location'            => 'nullable|string|max:255',
                'duration'            => 'nullable|string|max:255',
                'session_number'      => 'nullable|integer',
                'attendees_count'     => 'nullable|integer',
                'session_leader'      => 'nullable|string|max:255',
                'hosting_entity'      => 'nullable|string|max:255',
                'session_facilitator' => 'nullable|string|max:255',
                'attendees'           => 'nullable|array',
                'attendees.*.name'    => 'nullable|string|max:255',
                'attendees.*.name_en' => 'nullable|string|max:255',
                'attendees.*.id_number' => 'nullable|string|max:20',
                'attendees.*.phone'   => 'nullable|string|max:20',
                'attendees.*.specialty' => 'nullable|string|max:255',
                'attendees.*.governorate' => 'nullable|string|max:100',
                'attendees.*.birth_date' => 'nullable|date',
                'attendees.*.disability_type' => 'nullable|string|max:255',
                'attendees.*.marital_status' => 'nullable|string|max:100',
            ]);

            DB::transaction(function () use ($request, $group_support_session, $data) {
                $child = $group_support_session->replicate();
                $child->parent_id      = $group_support_session->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                if (!empty($data['attendees'])) {
                    foreach ($data['attendees'] as $att) {
                        $child->attendees()->create($att);
                    }
                }

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.group_support_session.edit', [$group_support_session, 'tab' => 'entry'])
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

        $group_support_session->update($data);

        return redirect()
            ->route('project_manager.services.group_support_session.edit', $group_support_session)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(GroupSupportSession $group_support_session): RedirectResponse
    {
        $group_support_session->delete();
        return redirect()->route('project_manager.services.group_support_session.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
