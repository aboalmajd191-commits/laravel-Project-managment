<?php

// app/Http/Controllers/Manager/DashboardController.php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Training;
use App\Models\EconomicProject;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $myProjectsQuery = Project::where('manager_id', $user->id);
        
        // Service Models to fetch standalone records from
        $serviceModels = [
            'training' => \App\Models\Training::class,
            'economic_empowerment' => \App\Models\EconomicProject::class,
            'awareness_workshop' => \App\Models\AwarenessWorkshop::class,
            'legal_consultation' => \App\Models\LegalConsultation::class,
            'psychological_consultation' => \App\Models\PsychologicalConsultation::class,
            'judicial_representation' => \App\Models\JudicialRepresentation::class,
            'legal_representation' => \App\Models\LegalRepresentation::class,
            'mediation' => \App\Models\Mediation::class,
            'individual_support_session' => \App\Models\IndividualSupportSession::class,
            'group_support_session' => \App\Models\GroupSupportSession::class,
        ];

        $allServiceProjects = collect();

        foreach ($serviceModels as $type => $model) {
            $records = $model::whereNull('parent_id')
                ->where('submitted_by', $user->id)
                ->get()
                ->map(function($item) use ($type) {
                    return (object)[
                        'id' => $item->id,
                        'name' => $item->project_name ?? $item->name,
                        'description' => $item->description ?? $item->funder ?? '',
                        'status' => $item->status,
                        'start_date' => $item->start_date ?? $item->project_date ?? null,
                        'end_date' => $item->end_date ?? null,
                        'service_type' => $type,
                        'model_type' => $type
                    ];
                });
            $allServiceProjects = $allServiceProjects->concat($records);
        }

        // Also get Shell Projects
        $shellProjects = $myProjectsQuery->latest()->get()->map(function($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'status' => $item->status,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'service_type' => $item->service_type ?? 'عام',
                'model_type' => 'project_shell'
            ];
        });

        $allProjects = $allServiceProjects->concat($shellProjects)->sortByDesc('id');
        
        $activeProjects = $allProjects->where('status', 'active');
        $completedProjects = $allProjects->where('status', 'completed');

        // Manual Pagination for the main table
        $page = request()->get('page', 1);
        $perPage = 10;
        $allProjectsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $allProjects->forPage($page, $perPage),
            $allProjects->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $stats = [
            'active_projects'    => $activeProjects->count(),
            'completed_projects' => $completedProjects->count(),
            'total_beneficiaries'=> $this->getMyBeneficiariesCount((int)$user->id),
            'pending_approvals'  => $this->getPendingApprovalsCount((int)$user->id),
        ];

        return view('manager.dashboard', [
            'stats'             => $stats,
            'allProjects'       => $allProjectsPaginated,
            'activeProjects'    => $activeProjects->take(5), // Keep dashboard lists concise
            'completedProjects' => $completedProjects->take(5),
        ]);
    }

    private function getMyBeneficiariesCount(int $managerId): int
    {
        $projectIds = Project::where('manager_id', $managerId)->pluck('id');
        
        $serviceModels = [
            \App\Models\Training::class,
            \App\Models\EconomicProject::class,
            \App\Models\AwarenessWorkshop::class,
            \App\Models\LegalConsultation::class,
            \App\Models\PsychologicalConsultation::class,
            \App\Models\JudicialRepresentation::class,
            \App\Models\LegalRepresentation::class,
            \App\Models\Mediation::class,
            \App\Models\IndividualSupportSession::class,
            \App\Models\GroupSupportSession::class,
        ];

        $total = 0;
        foreach ($serviceModels as $model) {
            $column = match($model) {
                \App\Models\Training::class => 'beneficiary_count',
                \App\Models\EconomicProject::class => 'individuals_count',
                \App\Models\AwarenessWorkshop::class => 'total_beneficiaries',
                default => null
            };

            if ($column) {
                $total += $model::whereIn('project_id', $projectIds)->sum($column);
            }
        }
        
        return (int) $total;
    }

    private function getPendingApprovalsCount(int $managerId): int
    {
        $serviceModels = [
            \App\Models\Training::class,
            \App\Models\EconomicProject::class,
            \App\Models\AwarenessWorkshop::class,
            \App\Models\LegalConsultation::class,
            \App\Models\PsychologicalConsultation::class,
            \App\Models\JudicialRepresentation::class,
            \App\Models\LegalRepresentation::class,
            \App\Models\Mediation::class,
            \App\Models\IndividualSupportSession::class,
            \App\Models\GroupSupportSession::class,
        ];

        $count = 0;
        foreach ($serviceModels as $model) {
            $count += $model::whereNotNull('parent_id')
                ->where('approval_status', 'pending')
                ->whereHas('parent', function($q) use ($managerId) {
                    $q->where('submitted_by', $managerId);
                })
                ->count();
        }
        
        return $count;
    }
}
