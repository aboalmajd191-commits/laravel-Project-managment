<?php

// app/Http/Controllers/Admin/DashboardController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Training;
use App\Models\EconomicProject;
use App\Models\AwarenessWorkshop;
use App\Models\LegalConsultation;
use App\Models\PsychologicalConsultation;
use App\Models\JudicialRepresentation;
use App\Models\LegalRepresentation;
use App\Models\Mediation;
use App\Models\IndividualSupportSession;
use App\Models\GroupSupportSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected \App\Services\ApprovalService $approvalService;

    public function __construct(\App\Services\ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $serviceModels = [
            'training'                   => Training::class,
            'economic_empowerment'       => EconomicProject::class,
            'awareness_workshop'         => AwarenessWorkshop::class,
            'legal_consultation'         => LegalConsultation::class,
            'psychological_consultation' => PsychologicalConsultation::class,
            'judicial_representation'    => JudicialRepresentation::class,
            'legal_representation'       => LegalRepresentation::class,
            'mediation'                  => Mediation::class,
            'individual_support_session' => IndividualSupportSession::class,
            'group_support_session'      => GroupSupportSession::class,
        ];

        $stats = [
            'users_total'         => User::count(),
            'users_active'        => User::where('is_active', true)->count(),
            'projects_total'      => 0,
            'projects_active'     => 0,
            'projects_completed'  => 0,
            'total_beneficiaries' => 0,
            'pending_approvals'   => 0,
        ];

        $projectsByType = collect();
        $filteredProjects = collect();

        foreach ($serviceModels as $type => $model) {
            $query = $model::query();

            // ─── Filters ──────────────────────────────────────────────────────────
            
            // Filter by Project Manager
            if ($request->filled('manager_id')) {
                $query->where(function($q) use ($request) {
                    $q->whereNull('parent_id')->where('submitted_by', $request->manager_id);
                    $q->orWhereHas('parent', function($pq) use ($request) {
                        $pq->where('submitted_by', $request->manager_id);
                    });
                });
            }

            // Filter by Data Entry User
            if ($request->filled('data_entry_id')) {
                $query->whereNotNull('parent_id')->where('submitted_by', $request->data_entry_id);
            }

            // Date Range
            if ($request->filled('start_date')) {
                $dateCol = ($type === 'economic_empowerment') ? 'project_date' : 'start_date';
                $query->where($dateCol, '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $dateCol = ($type === 'economic_empowerment') ? 'project_date' : 'end_date';
                $query->where($dateCol, '<=', $request->end_date);
            }

            // Funder
            if ($request->filled('funder')) {
                $funderCol = in_array($type, ['psychological_consultation', 'judicial_representation', 'legal_representation', 'mediation']) ? 'funding_agency' : 'funder';
                $query->where($funderCol, 'LIKE', '%' . $request->funder . '%');
            }

            // Name
            if ($request->filled('name')) {
                $nameCol = ($type === 'training') ? 'name' : 'project_name';
                $query->where($nameCol, 'LIKE', '%' . $request->name . '%');
            }

            // Global Beneficiary Search (Name, ID, Phone)
            if ($request->filled('beneficiary')) {
                $search = $request->beneficiary;
                $words = array_filter(explode(' ', $search));
                
                $query->where(function($q) use ($words, $type) {
                    $q->whereHas('children', function($cq) use ($words, $type) {
                        if (in_array($type, ['training', 'awareness_workshop', 'group_support_session'])) {
                            $cq->whereHas('attendees', function($aq) use ($words, $type) {
                                $aq->where(function($waq) use ($words, $type) {
                                    foreach ($words as $word) {
                                        $waq->where(function($sub) use ($word, $type) {
                                            if ($type === 'group_support_session') {
                                                $sub->where('beneficiary_name', 'LIKE', "%$word%")
                                                   ->orWhere('id_number', 'LIKE', "%$word%")
                                                   ->orWhere('mobile', 'LIKE', "%$word%");
                                            } else {
                                                $sub->where('name', 'LIKE', "%$word%")
                                                   ->orWhere('id_number', 'LIKE', "%$word%")
                                                   ->orWhere('phone', 'LIKE', "%$word%");
                                            }
                                        });
                                    }
                                });
                            });
                        } else {
                            // Define searchable columns for each model
                            $searchColumns = match($type) {
                                'economic_empowerment'       => ['owner_name', 'id_number'],
                                'legal_consultation'         => ['full_name', 'id_number', 'phone'],
                                'psychological_consultation' => ['full_name', 'id_number', 'mobile'],
                                'judicial_representation'    => ['beneficiary_name', 'id_number', 'mobile'],
                                'legal_representation'       => ['beneficiary_name', 'id_number', 'mobile'],
                                'mediation'                  => ['beneficiary_name', 'id_number', 'mobile'],
                                'individual_support_session' => ['beneficiary_name', 'mobile'],
                                default                      => ['full_name', 'beneficiary_name', 'owner_name', 'id_number', 'mobile', 'phone']
                            };

                            $cq->where(function($sq) use ($words, $searchColumns) {
                                foreach ($words as $word) {
                                    $sq->where(function($wsq) use ($word, $searchColumns) {
                                        foreach ($searchColumns as $col) {
                                            $wsq->orWhere($col, 'LIKE', "%$word%");
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            }

            // Type Filter logic check
            $matchesType = !$request->filled('service_type') || $request->service_type === $type;

            // ─── Accumulate Stats ────────────────────────────────────────────────
            if ($matchesType) {
                $stats['projects_total']     += $query->clone()->whereNull('parent_id')->count();
                $stats['projects_active']    += $query->clone()->whereNull('parent_id')->where('status', 'active')->count();
                $stats['projects_completed'] += $query->clone()->whereNull('parent_id')->where('status', 'completed')->count();
                $stats['pending_approvals']  += $query->clone()->whereNotNull('parent_id')->where('approval_status', 'pending')->count();

                if ($type === 'training') {
                    $stats['total_beneficiaries'] += $query->clone()->whereNull('parent_id')->sum('beneficiary_count');
                } elseif ($type === 'economic_empowerment') {
                    $stats['total_beneficiaries'] += $query->clone()->whereNull('parent_id')->sum('individuals_count');
                } elseif ($type === 'awareness_workshop') {
                    $stats['total_beneficiaries'] += $query->clone()->whereNull('parent_id')->sum('total_beneficiaries');
                } else {
                    $stats['total_beneficiaries'] += $query->clone()->whereNotNull('parent_id')->where('approval_status', 'approved')->count();
                }

                $projectsByType->push((object)[
                    'service_type' => $this->approvalService->getServiceLabel($type),
                    'count' => $query->clone()->whereNull('parent_id')->count()
                ]);

                // Collect filtered projects for the list
                $results = $query->clone()
                    ->whereNull('parent_id')
                    ->with('submitter')
                    ->withCount(['children as approved_children_count' => function ($q) {
                        $q->where('approval_status', 'approved');
                    }])
                    ->latest()
                    ->get()
                    ->map(function($item) use ($type) {
                        $item->service_type_key = $type;
                        $item->service_label = $this->approvalService->getServiceLabel($type);
                        return $item;
                    });
                
                $filteredProjects = $filteredProjects->concat($results);
            }
        }

        $managers   = User::where('role', 'project_manager')->get();
        $dataEntry  = User::where('role', 'data_entry')->get();

        // Sort unified projects by date
        $allProjects = $filteredProjects->sortByDesc('created_at')->values();

        // Pagination for unified projects
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $projects = new \Illuminate\Pagination\LengthAwarePaginator(
            $allProjects->forPage($currentPage, $perPage),
            $allProjects->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.dashboard', compact('stats', 'projectsByType', 'managers', 'dataEntry', 'projects'));
    }

    public function showService(string $type, int $id): View
    {
        $serviceModels = [
            'training'                   => Training::class,
            'economic_empowerment'       => EconomicProject::class,
            'awareness_workshop'         => AwarenessWorkshop::class,
            'legal_consultation'         => LegalConsultation::class,
            'psychological_consultation' => PsychologicalConsultation::class,
            'judicial_representation'    => JudicialRepresentation::class,
            'legal_representation'       => LegalRepresentation::class,
            'mediation'                  => Mediation::class,
            'individual_support_session' => IndividualSupportSession::class,
            'group_support_session'      => GroupSupportSession::class,
        ];

        if (!isset($serviceModels[$type])) abort(404);

        $model = $serviceModels[$type];
        $record = $model::with(['submitter'])->findOrFail($id);

        $children = $record->children()
            ->where('approval_status', 'approved')
            ->with('submitter')
            ->latest();

        // Load specific relations for children
        if ($type === 'training' || $type === 'awareness_workshop' || $type === 'group_support_session') {
            $children->with('attendees');
        } elseif ($type === 'economic_empowerment') {
            $children->with('images');
        } elseif ($type === 'individual_support_session') {
            $children->with('details');
        }

        $paginatedChildren = $children->paginate(10);

        return view('admin.services.show', [
            'record'   => $record,
            'children' => $paginatedChildren,
            'type'     => $type,
            'label'    => $this->approvalService->getServiceLabel($type)
        ]);
    }

}
