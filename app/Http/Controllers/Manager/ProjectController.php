<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProjectController extends Controller
{
    protected ApprovalService $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $serviceModels = $this->approvalService->getServiceModels();
        $allCombined = collect();

        // 1. Shell Projects
        $shellQuery = Project::where('manager_id', $user->id);
        if ($request->filled('service_type')) {
            $shellQuery->where('service_type', $request->service_type);
        }
        if ($request->filled('status')) {
            $shellQuery->where('status', $request->status);
        }
        
        $shellProjects = $shellQuery->get()->map(function($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'status' => $item->status,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'service_type' => $item->service_type ?? 'عام',
                'model_type' => 'project_shell',
                'edit_route' => route('project_manager.projects.edit', $item),
                'show_route' => route('project_manager.projects.show', $item),
            ];
        });
        $allCombined = $allCombined->concat($shellProjects);

        // 2. Standalone Service Projects
        foreach ($serviceModels as $type => $model) {
            if ($request->filled('service_type') && $request->service_type !== $type) continue;

            $recordQuery = $model::whereNull('parent_id')->where('submitted_by', $user->id);
            if ($request->filled('status')) {
                $recordQuery->where('status', $request->status);
            }

            $records = $recordQuery->get()->map(function($item) use ($type) {
                return (object)[
                    'id' => $item->id,
                    'name' => $item->project_name ?? $item->name,
                    'description' => $item->description ?? ($item->funder ?? ''),
                    'status' => $item->status,
                    'start_date' => $item->start_date ?? $item->project_date ?? null,
                    'end_date' => $item->end_date ?? null,
                    'service_type' => $type,
                    'model_type' => $type,
                    'edit_route' => route('project_manager.services.'.$type.'.edit', $item),
                    'show_route' => route('project_manager.services.'.$type.'.show', $item),
                ];
            });
            $allCombined = $allCombined->concat($records);
        }

        $allCombined = $allCombined->sortByDesc('id');

        // Manual Pagination
        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $projects = new LengthAwarePaginator(
            $allCombined->forPage($page, $perPage),
            $allCombined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('manager.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);
        $dataEntryUsers = User::where('role', 'data_entry')->where('is_active', true)->get();
        
        return view('manager.projects.create', compact('dataEntryUsers'));
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['manager_id'] = auth()->id();

        $project = Project::create($data);

        if (!empty($data['assigned_users'])) {
            $project->dataEntryUsers()->sync($data['assigned_users']);
        }

        return redirect()->route('project_manager.projects.index')
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load(['dataEntryUsers']);

        return view('manager.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        $dataEntryUsers = User::where('role', 'data_entry')->where('is_active', true)->get();
        $assignedUserIds = $project->dataEntryUsers->pluck('id')->toArray();

        return view('manager.projects.edit', compact('project', 'dataEntryUsers', 'assignedUserIds'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = $request->validated();
        $project->update($data);

        if (isset($data['assigned_users'])) {
            $project->dataEntryUsers()->sync($data['assigned_users']);
        }

        return redirect()->route('project_manager.projects.index')
            ->with('success', 'تم تحديث بيانات المشروع بنجاح');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('project_manager.projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }
}

