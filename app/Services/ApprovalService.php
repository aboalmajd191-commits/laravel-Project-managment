<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApprovalService
{
    /**
     * Get the mapping of service keys to their respective models.
     */
    public function getServiceModels(): array
    {
        return [
            'training'                   => \App\Models\Training::class,
            'economic_empowerment'       => \App\Models\EconomicProject::class,
            'awareness_workshop'         => \App\Models\AwarenessWorkshop::class,
            'legal_consultation'         => \App\Models\LegalConsultation::class,
            'psychological_consultation' => \App\Models\PsychologicalConsultation::class,
            'judicial_representation'    => \App\Models\JudicialRepresentation::class,
            'legal_representation'       => \App\Models\LegalRepresentation::class,
            'mediation'                  => \App\Models\Mediation::class,
            'individual_support_session' => \App\Models\IndividualSupportSession::class,
            'group_support_session'      => \App\Models\GroupSupportSession::class,
        ];
    }

    /**
     * Get the model class for a given type.
     */
    public function getModelClass(string $type): string
    {
        $models = $this->getServiceModels();
        
        if (!isset($models[$type])) {
            abort(404, "Unknown service type: {$type}");
        }

        return $models[$type];
    }

    /**
     * Get a specific record by type and ID.
     */
    public function getRecord(string $type, int $id)
    {
        $modelClass = $this->getModelClass($type);
        return $modelClass::findOrFail($id);
    }

    /**
     * Get all pending approvals across all service models.
     */
    public function getPendingApprovals(User $user, int $perPage = 10): LengthAwarePaginator
    {
        $models = $this->getServiceModels();
        $pendingApprovalsList = collect();

        foreach ($models as $type => $model) {
            $records = $model::with(['submitter', 'parent'])
                ->whereNotNull('parent_id')
                ->where('approval_status', 'pending')
                ->when(!$user->isAdmin(), function($q) use ($user) {
                    $q->whereHas('parent', function($pq) use ($user) {
                        $pq->where('submitted_by', $user->id);
                    });
                })
                ->latest()
                ->get()
                ->map(function($record) use ($type) {
                    $record->type_key = $type;
                    return $record;
                });
            $pendingApprovalsList = $pendingApprovalsList->concat($records);
        }

        $pendingApprovalsList = $pendingApprovalsList->sortByDesc('created_at');

        $page = request()->get('page', 1);
        
        return new LengthAwarePaginator(
            $pendingApprovalsList->forPage($page, $perPage),
            $pendingApprovalsList->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Get the total count of pending approvals for a user.
     */
    public function getPendingCount(User $user): int
    {
        $models = $this->getServiceModels();
        $total = 0;

        foreach ($models as $model) {
            $count = $model::whereNotNull('parent_id')
                ->where('approval_status', 'pending')
                ->when(!$user->isAdmin(), function($q) use ($user) {
                    $q->whereHas('parent', function($pq) use ($user) {
                        $pq->where('submitted_by', $user->id);
                    });
                })
                ->count();
            $total += $count;
        }

        return $total;
    }

    /**
     * Approve a record.
     */
    public function approve($record, int $approverId): void
    {
        $record->update([
            'approval_status' => 'approved',
            'approved_by'     => $approverId,
            'approved_at'     => now(),
        ]);
    }

    /**
     * Reject a record.
     */
    public function reject($record, string $reason): void
    {
        $record->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Get the human-readable label for a service type.
     */
    public function getServiceLabel(string $type): string
    {
        return match($type) {
            'training'                   => 'تدريب وبناء قدرات',
            'economic_empowerment'       => 'التمكين الاقتصادي',
            'awareness_workshop'         => 'ورش توعوية',
            'legal_consultation'         => 'استشارة قانونية',
            'psychological_consultation' => 'استشارة نفسية',
            'judicial_representation'    => 'تمثيل قضائي',
            'legal_representation'       => 'تمثيل قانوني',
            'mediation'                  => 'وساطة',
            'individual_support_session' => 'جلسات فردية',
            'group_support_session'      => 'جلسات جماعية',
            default                      => $type
        };
    }
}
