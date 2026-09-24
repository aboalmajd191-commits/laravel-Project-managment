<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LegalRepresentation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class LegalRepresentationController extends Controller
{
    public function index(): View
    {
        $representations = LegalRepresentation::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.legal_representation.index', compact('representations'));
    }

    public function create(): View
    {
        return view('manager.services.legal_representation.create');
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

        LegalRepresentation::create($data);

        return redirect()->route('project_manager.services.legal_representation.index')
            ->with('success', 'تم إنشاء سجل التمثيل القانوني بنجاح.');
    }

    public function show(LegalRepresentation $legal_representation): View
    {
        $legal_representation->load(['submitter', 'children.submitter']);
        return view('manager.services.legal_representation.show', compact('legal_representation'));
    }

    public function edit(LegalRepresentation $legal_representation): View
    {
        $legal_representation->load('children.submitter');
        return view('manager.services.legal_representation.edit', compact('legal_representation'));
    }

    public function update(Request $request, LegalRepresentation $legal_representation): RedirectResponse
    {
        if ($request->tab === 'entry') {
            $data = $request->validate([
                'beneficiary_name'    => 'nullable|string|max:255',
                'id_number'           => 'nullable|string|max:20',
                'region'              => 'nullable|string|max:100',
                'mobile'              => 'nullable|string|max:20',
                'has_disability'      => 'nullable|boolean',
                'marital_status'      => 'nullable|string|max:50',
                'individuals_count'   => 'nullable|integer|min:0',
                'health_status'       => 'nullable|string|max:255',
                'cases_count'         => 'nullable|integer|min:0',
                'extract_type'        => 'nullable|string|max:100',
                'extract_type_detail' => 'nullable|string|max:255',
                'extraction_date'     => 'nullable|date',
                'amount'              => 'nullable|numeric|min:0',
            ]);

            DB::transaction(function () use ($request, $legal_representation, $data) {
                $child = $legal_representation->replicate();
                $child->parent_id      = $legal_representation->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.legal_representation.edit', [$legal_representation, 'tab' => 'entry'])
                ->with('success', 'تم إضافة التمثيل القانوني بنجاح. يمكنك إضافة المزيد.');
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

        $legal_representation->update($data);

        return redirect()
            ->route('project_manager.services.legal_representation.edit', $legal_representation)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(LegalRepresentation $legal_representation): RedirectResponse
    {
        $legal_representation->delete();
        return redirect()->route('project_manager.services.legal_representation.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
