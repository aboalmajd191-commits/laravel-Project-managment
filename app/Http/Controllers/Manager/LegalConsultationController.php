<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LegalConsultation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class LegalConsultationController extends Controller
{
    public function index(): View
    {
        $consultations = LegalConsultation::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.legal_consultation.index', compact('consultations'));
    }

    public function create(): View
    {
        return view('manager.services.legal_consultation.create');
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

        LegalConsultation::create($data);

        return redirect()->route('project_manager.services.legal_consultation.index')
            ->with('success', 'تم إنشاء سجل الاستشارات القانونية بنجاح.');
    }

    public function show(LegalConsultation $legal_consultation): View
    {
        $legal_consultation->load(['submitter', 'children.submitter']);
        return view('manager.services.legal_consultation.show', compact('legal_consultation'));
    }

    public function edit(LegalConsultation $legal_consultation): View
    {
        $legal_consultation->load('children.submitter');
        return view('manager.services.legal_consultation.edit', compact('legal_consultation'));
    }

    public function update(Request $request, LegalConsultation $legal_consultation): RedirectResponse
    {
        if ($request->tab === 'entry') {
            $data = $request->validate([
                'full_name'           => 'nullable|string|max:255',
                'id_number'           => 'nullable|string|max:20',
                'birth_date'          => 'nullable|date',
                'marital_status'      => 'nullable|string|max:50',
                'gender'              => 'nullable|in:male,female',
                'phone'               => 'nullable|string|max:20',
                'current_address'     => 'nullable|string|max:255',
                'previous_address'    => 'nullable|string|max:255',
                'displacement_count'  => 'nullable|integer|min:0',
                'has_disability'      => 'nullable|boolean',
                'source'              => 'nullable|string|max:255',
                'problem_description' => 'nullable|string',
                'legal_aid_details'   => 'nullable|string',
            ]);

            DB::transaction(function () use ($request, $legal_consultation, $data) {
                $child = $legal_consultation->replicate();
                $child->parent_id      = $legal_consultation->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.legal_consultation.edit', [$legal_consultation, 'tab' => 'entry'])
                ->with('success', 'تم إضافة الاستشارة بنجاح. يمكنك إضافة المزيد.');
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

        $legal_consultation->update($data);

        return redirect()
            ->route('project_manager.services.legal_consultation.edit', $legal_consultation)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(LegalConsultation $legal_consultation): RedirectResponse
    {
        $legal_consultation->delete();
        return redirect()->route('project_manager.services.legal_consultation.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
