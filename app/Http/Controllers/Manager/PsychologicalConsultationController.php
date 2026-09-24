<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PsychologicalConsultation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PsychologicalConsultationController extends Controller
{
    public function index(): View
    {
        $consultations = PsychologicalConsultation::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.psychological_consultation.index', compact('consultations'));
    }

    public function create(): View
    {
        return view('manager.services.psychological_consultation.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funding_agency'      => 'required|string|max:255',
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

        PsychologicalConsultation::create($data);

        return redirect()->route('project_manager.services.psychological_consultation.index')
            ->with('success', 'تم إنشاء سجل الاستشارات النفسية بنجاح.');
    }

    public function show(PsychologicalConsultation $psychological_consultation): View
    {
        $psychological_consultation->load(['submitter', 'children.submitter']);
        return view('manager.services.psychological_consultation.show', compact('psychological_consultation'));
    }

    public function edit(PsychologicalConsultation $psychological_consultation): View
    {
        $psychological_consultation->load('children.submitter');
        return view('manager.services.psychological_consultation.edit', compact('psychological_consultation'));
    }

    public function update(Request $request, PsychologicalConsultation $psychological_consultation): RedirectResponse
    {
        if ($request->tab === 'entry') {
            $data = $request->validate([
                'full_name'            => 'nullable|string|max:255',
                'case_code'            => 'nullable|string|max:50',
                'id_number'            => 'nullable|string|max:20',
                'age'                  => 'nullable|integer|min:0',
                'mobile'               => 'nullable|string|max:20',
                'marital_status'       => 'nullable|string|max:50',
                'education_level'      => 'nullable|string|max:100',
                'mission'              => 'nullable|string|max:255',
                'displacement_status'  => 'nullable|string|max:100',
                'original_governorate' => 'nullable|string|max:100',
                'primary_address'      => 'nullable|string|max:255',
                'displacement_address' => 'nullable|string|max:255',
                'health_status'        => 'nullable|string|max:255',
                'disability_type'      => 'nullable|string|max:255',
                'case_description'     => 'nullable|string',
                'procedure_guidance'   => 'nullable|string',
                'recommendations'      => 'nullable|string',
            ]);

            DB::transaction(function () use ($request, $psychological_consultation, $data) {
                $child = $psychological_consultation->replicate();
                $child->parent_id      = $psychological_consultation->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.psychological_consultation.edit', [$psychological_consultation, 'tab' => 'entry'])
                ->with('success', 'تم إضافة الاستشارة بنجاح. يمكنك إضافة المزيد.');
        }

        $data = $request->validate([
            'project_name'        => 'required|string|max:255',
            'funding_agency'      => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'status'              => 'required|in:draft,active,completed',
            'project_id'          => 'nullable|exists:projects,id',
            'sector_type'         => 'required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ]);

        $psychological_consultation->update($data);

        return redirect()
            ->route('project_manager.services.psychological_consultation.edit', $psychological_consultation)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(PsychologicalConsultation $psychological_consultation): RedirectResponse
    {
        $psychological_consultation->delete();
        return redirect()->route('project_manager.services.psychological_consultation.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
