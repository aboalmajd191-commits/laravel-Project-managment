<?php

// app/Models/Training.php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes, HasServiceAttachments;

    protected $fillable = [
        'name',
        'parent_project',
        'beneficiary_count',
        'start_date',
        'end_date',
        'funder',
        'gender_type',
        'notes',
        'description',
        'activity_name',
        'project_description',
        'governorate',
        'status',
        'sector_type',
        'parent_id',
        'submitted_by',
        'approved_by',
        'approval_status',
        'rejection_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date'  => 'date',
            'end_date'    => 'date',
            'approved_at' => 'datetime',
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
        return $this->belongsTo(Training::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Training::class, 'parent_id');
    }

    /** من أدخل البيانات */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /** من وافق على التدريب */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** المشاركون في التدريب */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
