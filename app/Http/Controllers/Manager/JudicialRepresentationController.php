<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\JudicialRepresentation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class JudicialRepresentationController extends Controller
{
    public function index(): View
    {
        $representations = JudicialRepresentation::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.judicial_representation.index', compact('representations'));
    }

    public function create(): View
    {
        return view('manager.services.judicial_representation.create');
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

        JudicialRepresentation::create($data);

        return redirect()->route('project_manager.services.judicial_representation.index')
            ->with('success', 'تم إنشاء سجل التمثيل القضائي بنجاح.');
    }

    public function show(JudicialRepresentation $judicial_representation): View
    {
        $judicial_representation->load(['submitter', 'children.submitter']);
        return view('manager.services.judicial_representation.show', compact('judicial_representation'));
    }

    public function edit(JudicialRepresentation $judicial_representation): View
    {
        $judicial_representation->load('children.submitter');
        return view('manager.services.judicial_representation.edit', compact('judicial_representation'));
    }

    public function update(Request $request, JudicialRepresentation $judicial_representation): RedirectResponse
    {
        if ($request->tab === 'entry') {
            $data = $request->validate([
                'beneficiary_name'  => 'nullable|string|max:255',
                'id_number'         => 'nullable|string|max:20',
                'region'            => 'nullable|string|max:100',
                'mobile'            => 'nullable|string|max:20',
                'has_disability'    => 'nullable|boolean',
                'marital_status'    => 'nullable|string|max:50',
                'individuals_count' => 'nullable|integer|min:0',
                'health_status'     => 'nullable|string|max:255',
                'cases_count'       => 'nullable|integer|min:0',
                'case_type'         => 'nullable|string|max:100',
                'case_number'       => 'nullable|string|max:100',
                'case_name_type'    => 'nullable|string|max:255',
                'court_name'        => 'nullable|string|max:255',
                'lawsuit_date'      => 'nullable|date',
                'case_status'       => 'nullable|string|max:50',
                'closing_date'      => 'nullable|date',
                'total_paid'        => 'nullable|numeric|min:0',
            ]);

            DB::transaction(function () use ($request, $judicial_representation, $data) {
                $child = $judicial_representation->replicate();
                $child->parent_id      = $judicial_representation->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.judicial_representation.edit', [$judicial_representation, 'tab' => 'entry'])
                ->with('success', 'تم إضافة التمثيل القضائي بنجاح. يمكنك إضافة المزيد.');
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

        $judicial_representation->update($data);

        return redirect()
            ->route('project_manager.services.judicial_representation.edit', $judicial_representation)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(JudicialRepresentation $judicial_representation): RedirectResponse
    {
        $judicial_representation->delete();
        return redirect()->route('project_manager.services.judicial_representation.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
