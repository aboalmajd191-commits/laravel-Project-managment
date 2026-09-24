<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\EconomicRequest;
use App\Models\EconomicProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EconomicController extends Controller
{
    public function index(): View
    {
        $economics = EconomicProject::whereNull('parent_id')
            ->with('submitter')
            ->withCount('children')
            ->latest()
            ->paginate(10);

        return view('manager.services.economic.index', compact('economics'));
    }

    public function create(): View
    {
        return view('manager.services.economic.create');
    }

    public function store(EconomicRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['submitted_by'] = auth()->id();
        $data['approval_status'] = 'approved';
        $data['parent_id'] = null;

        EconomicProject::create($data);

        return redirect()->route('project_manager.services.economic_empowerment.index')
            ->with('success', 'تم إنشاء مشروع التمكين بنجاح.');
    }

    public function show(EconomicProject $economic_empowerment): View
    {
        $economic_empowerment->load('submitter', 'children.submitter', 'children.images');
        return view('manager.services.economic.show', ['economic' => $economic_empowerment]);
    }

    public function edit(EconomicProject $economic_empowerment): View
    {
        $economic_empowerment->load('children.submitter');
        return view('manager.services.economic.edit', ['economic' => $economic_empowerment]);
    }

    public function update(EconomicRequest $request, EconomicProject $economic_empowerment): RedirectResponse
    {
        if ($request->has('tab') && $request->tab === 'entry') {
            // New child entry for Economic Project (Manager creating directly -> auto approved)
            $data = $request->validated();

            DB::transaction(function () use ($request, $economic_empowerment, $data) {
                $child = $economic_empowerment->replicate();
                $child->parent_id      = $economic_empowerment->id;
                $child->submitted_by   = auth()->id();
                $child->approval_status = 'approved';
                
                $child->fill(\Illuminate\Support\Arr::except($data, 'images'));
                $child->save();

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $img) {
                        $path = $img->store("economic_projects/{$child->id}", 'private');
                        $child->images()->create(['path' => $path]);
                    }
                }

                $child->handleAttachments($request);
            });

            return redirect()
                ->route('project_manager.services.economic_empowerment.edit', [$economic_empowerment, 'tab' => 'entry'])
                ->with('success', 'تم إضافة المستفيد بنجاح. يمكنك إضافة المزيد.');
        }

        // Basic Data Update
        $data = $request->validated();
        $economic_empowerment->update($data);

        return redirect()
            ->route('project_manager.services.economic_empowerment.edit', $economic_empowerment)
            ->with('success', 'تم تحديث البيانات الأساسية بنجاح.');
    }

    public function destroy(EconomicProject $economic_empowerment): RedirectResponse
    {
        $economic_empowerment->delete();
        return redirect()->route('project_manager.services.economic_empowerment.index')
            ->with('success', 'تم حذف المشروع بنجاح.');
    }
}

