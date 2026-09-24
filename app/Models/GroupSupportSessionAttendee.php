<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupSupportSessionAttendee extends Model
{
    use \App\Traits\HasServiceAttachments;

    protected $fillable = [
        'group_session_id',
        'beneficiary_name',
        'name_en',
        'id_number',
        'mobile',
        'gender',
        'birth_date',
        'governorate',
        'marital_status',
        'health_status',
        'disability_type',
        'displacement_count',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(GroupSupportSession::class, 'group_session_id');
    }
}
