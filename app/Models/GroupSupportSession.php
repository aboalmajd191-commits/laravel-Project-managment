<?php

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSupportSession extends Model
{
    use SoftDeletes, HasServiceAttachments;

    protected $fillable = [
        'project_id',
        'parent_id',
        'project_name',
        'funder',
        'start_date',
        'end_date',
        'target_count',
        'session_name',
        'location',
        'duration',
        'session_number',
        'attendees_count',
        'session_leader',
        'hosting_entity',
        'session_facilitator',
        'description',
        'project_description',
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

    public function attendees(): HasMany
    {
        return $this->hasMany(GroupSupportSessionAttendee::class, 'group_session_id');
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
