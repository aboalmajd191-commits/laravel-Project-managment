<?php

// app/Policies/ProjectPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * من يمكنه رؤية قائمة المشاريع.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProjectManager();
    }

    /**
     * من يمكنه رؤية تفاصيل مشروع معين.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProjectManager()) {
            return $project->manager_id === $user->id;
        }

        if ($user->isDataEntry()) {
            return $project->status === 'active' && $project->dataEntryUsers->contains($user->id);
        }

        return false;
    }

    /**
     * من يمكنه إنشاء مشروع (فقط الأدمن والمدراء حالياً).
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProjectManager();
    }

    /**
     * من يمكنه تعديل المشروع.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($user->isProjectManager() && $project->manager_id === $user->id);
    }

    /**
     * من يمكنه الحذف.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($user->isProjectManager() && $project->manager_id === $user->id);
    }
}
