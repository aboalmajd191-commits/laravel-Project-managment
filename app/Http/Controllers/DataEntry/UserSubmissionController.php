<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\EconomicProject;
use Illuminate\View\View;

class UserSubmissionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
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

        $submissions = collect();

        foreach ($serviceModels as $type => $model) {
            $records = $model::where('submitted_by', $user->id)
                ->latest()
                ->get()
                ->map(function($record) use ($type) {
                    return (object)[
                        'id' => $record->id,
                        'name' => $record->project_name ?? $record->name ?? $record->owner_name ?? $record->beneficiary_name ?? 'إدخال بدون اسم',
                        'activity_name' => $record->activity_name ?? null,
                        'service_type' => $type,
                        'approval_status' => $record->approval_status,
                        'created_at' => $record->created_at,
                        'edit_url' => route('data_entry.services.' . $type . '.edit', $record->id)
                    ];
                });
            $submissions = $submissions->concat($records);
        }

        $submissions = $submissions->sortByDesc('created_at');
        
        // Calculate Stats
        $stats = [
            'total'     => $submissions->count(),
            'approved'  => $submissions->where('approval_status', 'approved')->count(),
            'pending'   => $submissions->where('approval_status', 'pending')->count(),
        ];

        // Manual Pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $submissionsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $submissions->forPage($page, $perPage),
            $submissions->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('entry.submissions.index', [
            'submissions' => $submissionsPaginated,
            'stats'       => $stats
        ]);
    }
}
