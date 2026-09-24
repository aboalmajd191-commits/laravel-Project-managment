<?php

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualSupportSession extends Model
{
    use SoftDeletes, HasServiceAttachments;

    protected $fillable = [
        'project_id',
        'parent_id',
        'project_name',
        'funder',
        'start_date',
        'end_date',
        'specialist',
        'beneficiary_name',
        'name_en',
        'case_code',
        'birth_date',
        'mobile',
        'address',
        'education_level',
        'marital_status',
        'main_complaint',
        'description',
        'project_description',
        'disability_type',
        'submitted_by',
        'approved_by',
        'approved_at',
        'approval_status',
        'status',
        'sector_type',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'birth_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(IndividualSupportSessionDetail::class, 'session_entry_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
