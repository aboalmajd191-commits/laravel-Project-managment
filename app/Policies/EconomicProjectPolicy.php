<?php

// app/Policies/EconomicProjectPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EconomicProject;
use App\Models\User;

class EconomicProjectPolicy
{
    /**
     * من يمكنه رؤية قائمة المشاريع الاقتصادية.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProjectManager() || $user->isDataEntry();
    }

    /**
     * من يمكنه تعديل المشروع الاقتصادي.
     */
    public function update(User $user, EconomicProject $economic): bool
    {
        if ($user->isAdmin()) return true;
        
        if ($user->isDataEntry()) {
            return $economic->submitted_by === $user->id && $economic->approval_status !== 'approved';
        }

        return false;
    }

    /**
     * من يمكنه الحذف.
     */
    public function delete(User $user, EconomicProject $economic): bool
    {
        if ($user->isAdmin()) return true;

        if ($user->isDataEntry()) {
            return $economic->submitted_by === $user->id && $economic->approval_status !== 'approved';
        }

        return false;
    }
}
