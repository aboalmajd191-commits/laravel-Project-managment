<?php

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JudicialRepresentation extends Model
{
    use SoftDeletes, HasServiceAttachments;

    protected $fillable = [
        'project_id',
        'parent_id',
        'project_name',
        'funding_agency',
        'start_date',
        'end_date',
        'beneficiary_name',
        'name_en',
        'birth_date',
        'description',
        'id_number',
        'region',
        'mobile',
        'disability_type',
        'marital_status',
        'individuals_count',
        'health_status',
        'project_description',
        'cases_count',
        'case_type',
        'case_number',
        'case_name_type',
        'court_name',
        'lawsuit_date',
        'case_status',
        'closing_date',
        'total_paid',
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
            'lawsuit_date' => 'date',
            'closing_date' => 'date',
            'approved_at' => 'datetime',
            'total_paid' => 'decimal:2',
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

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
