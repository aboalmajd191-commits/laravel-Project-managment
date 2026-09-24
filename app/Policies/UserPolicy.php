<?php

// app/Policies/UserPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * فقط مدير النظام يمكنه رؤية قائمة المستخدمين.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * فقط مدير النظام يمكنه إنشاء مستخدمين جدد.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * فقط مدير النظام يمكنه تعديل المستخدمين.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * فقط مدير النظام يمكنه حذف المستخدمين (ما عدا نفسه).
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
