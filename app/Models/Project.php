<?php

// app/Models/Project.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'service_type',
        'sector_type',
        'status',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** مدير المشروع */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /** مدخلو البيانات المعيّنون على المشروع */
    public function dataEntryUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    /** التدريبات التابعة للمشروع */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    /** المشاريع الاقتصادية التابعة للمشروع */
    public function economicProjects(): HasMany
    {
        return $this->hasMany(EconomicProject::class);
    }
}
