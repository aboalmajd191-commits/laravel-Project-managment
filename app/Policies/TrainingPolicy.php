<?php

// app/Policies/TrainingPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Training;
use App\Models\User;

class TrainingPolicy
{
    /**
     * من يمكنه رؤية قائمة التدريبات.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProjectManager() || $user->isDataEntry();
    }

    /**
     * من يمكنه تعديل التدريب.
     */
    public function update(User $user, Training $training): bool
    {
        if ($user->isAdmin()) return true;
        
        // Data entry only if they submitted it and it's not approved yet
        if ($user->isDataEntry()) {
            return $training->submitted_by === $user->id && $training->approval_status !== 'approved';
        }

        return false;
    }

    /**
     * من يمكنه الحذف.
     */
    public function delete(User $user, Training $training): bool
    {
        if ($user->isAdmin()) return true;

        if ($user->isDataEntry()) {
            return $training->submitted_by === $user->id && $training->approval_status !== 'approved';
        }

        return false;
    }
}
