<?php

// app/Models/EconomicProject.php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicProject extends Model
{
    use SoftDeletes, HasServiceAttachments;

    protected $fillable = [
        'project_name',
        'parent_project',
        'coordinator_name',
        'start_date',
        'end_date',
        'project_date',
        'funder',
        'total_grant_value',
        'owner_name',
        'name_en',
        'description',
        'project_description',
        'disability_type',
        'grant_value',
        'grant_date',
        'birth_date',
        'individuals_count',
        'id_number',
        'marital_status',
        'education_level',
        'governorate',
        'submitted_by',
        'approved_by',
        'approval_status',
        'rejection_reason',
        'parent_id',
        'status',
        'sector_type',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date'        => 'date',
            'end_date'          => 'date',
            'project_date'      => 'date',
            'grant_date'        => 'date',
            'birth_date'        => 'date',
            'approved_at'       => 'datetime',
            'total_grant_value' => 'decimal:2',
            'grant_value'       => 'decimal:2',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(EconomicProject::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(EconomicProject::class, 'parent_id');
    }

    /** من أدخل البيانات */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /** من وافق على المشروع */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** الصور المرفقة بالمشروع الاقتصادي */
    public function images(): HasMany
    {
        return $this->hasMany(EconomicProjectImage::class);
    }
}
