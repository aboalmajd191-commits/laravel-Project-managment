<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Mediation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class MediationController extends Controller
{
    public function index(): View
    {
        $mediations = Mediation::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('manager.services.mediation.index', compact('mediations'));
    }

    public function create(): View
    {
        return view('manager.services.mediation.create');
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

        Mediation::create($data);

        return redirect()->route('project_manager.services.mediation.index')
            ->with('success', 'تم إنشاء سجل الوساطة بنجاح.');
    }

    public function show(Mediation $mediation): View
    {
        $mediation->load(['submitter', 'children.submitter']);
        return view('manager.services.mediation.show', compact('mediation'));
    }

    public function edit(Mediation $mediation): View
    {
        $mediation->load('children.submitter');
        return view('manager.services.mediation.edit', compact('mediation'));
    }

    public function update(Request $request, Mediation $mediation): RedirectResponse
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
                'mediation_type'    => 'nullable|string|max:100',
                'extraction_date'   => 'nullable|date',
                'amount'            => 'nullable|numeric|min:0',
            ]);

            DB::transaction(function () use ($request, $mediation, $data) {
                $child = $mediation->replicate();
                $child->parent_id      = $mediation->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill($data);
                $child->save();

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.mediation.edit', [$mediation, 'tab' => 'entry'])
                ->with('success', 'تم إضافة الوساطة بنجاح. يمكنك إضافة المزيد.');
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

        $mediation->update($data);

        return redirect()
            ->route('project_manager.services.mediation.edit', $mediation)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(Mediation $mediation): RedirectResponse
    {
        $mediation->delete();
        return redirect()->route('project_manager.services.mediation.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
