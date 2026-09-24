<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AwarenessWorkshop;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AwarenessWorkshopController extends Controller
{
    public function index(): View
    {
        $workshops = AwarenessWorkshop::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);

        return view('manager.services.awareness_workshop.index', compact('workshops'));
    }

    public function create(): View
    {
        return view('manager.services.awareness_workshop.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funder'              => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'total_beneficiaries' => 'required|integer|min:0',
            'status'              => 'required|in:draft,active,completed',
            'sector_type'         => 'required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ]);

        $data['submitted_by'] = auth()->id();
        $data['approval_status'] = 'approved';
        $data['parent_id'] = null;

        AwarenessWorkshop::create($data);

        return redirect()->route('project_manager.services.awareness_workshop.index')
            ->with('success', 'تم إنشاء الورشة بنجاح.');
    }

    public function show(AwarenessWorkshop $awareness_workshop): View
    {
        $awareness_workshop->load('submitter', 'children.submitter');
        return view('manager.services.awareness_workshop.show', compact('awareness_workshop'));
    }

    public function edit(AwarenessWorkshop $awareness_workshop): View
    {
        $awareness_workshop->load('children.submitter');
        return view('manager.services.awareness_workshop.edit', compact('awareness_workshop'));
    }

    public function update(Request $request, AwarenessWorkshop $awareness_workshop): RedirectResponse
    {
        if ($request->tab === 'entry') {
            // Add a new session entry (child record) — all optional
            $data = $request->validate([
                'meeting_name'        => 'nullable|string|max:255',
                'meeting_location'    => 'nullable|string|max:255',
                'meeting_duration'    => 'nullable|string|max:255',
                'session_number'      => 'nullable|string|max:50',
                'attendance_count'    => 'nullable|integer|min:0',
                'meeting_moderator'   => 'nullable|string|max:255',
                'hosting_party'       => 'nullable|string|max:255',
                'session_facilitator' => 'nullable|string|max:255',
                'attendees'           => 'nullable|array',
                'attendees.*.name'    => 'nullable|string|max:255',
                'attendees.*.name_en' => 'nullable|string|max:255',
                'attendees.*.id_number' => 'nullable|string|max:20',
                'attendees.*.specialty' => 'nullable|string|max:255',
                'attendees.*.birth_date' => 'nullable|date',
                'attendees.*.phone'   => 'nullable|string|max:20',
                'attendees.*.governorate' => 'nullable|string|max:100',
                'attendees.*.marital_status' => 'nullable|string|max:255',
                'attendees.*.disability_type' => 'nullable|string|max:255',
            ]);
            
            $attendees = $data['attendees'] ?? [];
            $data = \Illuminate\Support\Arr::except($data, ['attendees']);

            \DB::transaction(function () use ($request, $awareness_workshop, $data, $attendees) {
                $child = $awareness_workshop->replicate();
                $child->parent_id      = $awareness_workshop->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                $child->fill($data);
                $child->save();

                foreach ($attendees as $att) {
                    if (!empty($att['name'])) {
                        $child->attendees()->create($att);
                    }
                }

                $child->handleAttachments($request);
            });

            // Redirect back to edit entry tab so manager can add another session
            return redirect()
                ->route('project_manager.services.awareness_workshop.edit', [$awareness_workshop, 'tab' => 'entry'])
                ->with('success', 'تم إضافة الجلسة بنجاح. يمكنك إضافة جلسة أخرى.');
        }

        // Basic data update
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funder'              => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'total_beneficiaries' => 'required|integer|min:0',
            'status'              => 'required|in:draft,active,completed',
            'sector_type'         => 'sometimes|required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ]);

        $awareness_workshop->update($data);

        return redirect()
            ->route('project_manager.services.awareness_workshop.edit', $awareness_workshop)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(AwarenessWorkshop $awareness_workshop): RedirectResponse
    {
        $awareness_workshop->delete();
        return redirect()->route('project_manager.services.awareness_workshop.index')
            ->with('success', 'تم حذف الورشة بنجاح.');
    }
}
