<?php

// app/Models/User.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'id_number',
        'specialty',
        'governorate',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProjectManager(): bool
    {
        return $this->role === 'project_manager';
    }

    public function isDataEntry(): bool
    {
        return $this->role === 'data_entry';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** المشاريع التي يديرها هذا المستخدم (مدير مشروع) */
    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    /** المشاريع التي يشتغل عليها مدخل البيانات (pivot) */
    public function assignedProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    /** التدريبات التي أدخلها هذا المستخدم */
    public function submittedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'submitted_by');
    }

    /** التدريبات التي وافق عليها هذا المستخدم */
    public function approvedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'approved_by');
    }

    /** المشاريع الاقتصادية التي أدخلها هذا المستخدم */
    public function submittedEconomicProjects(): HasMany
    {
        return $this->hasMany(EconomicProject::class, 'submitted_by');
    }

    /** المشاريع الاقتصادية التي وافق عليها هذا المستخدم */
    public function approvedEconomicProjects(): HasMany
    {
        return $this->hasMany(EconomicProject::class, 'approved_by');
    }
}
