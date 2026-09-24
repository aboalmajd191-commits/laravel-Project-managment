<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualSupportSessionDetail extends Model
{
    protected $fillable = [
        'session_entry_id',
        'session_number',
        'session_date',
        'session_time',
        'intervention_file',
        'intervention_summary',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
        ];
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(IndividualSupportSession::class, 'session_entry_id');
    }
}
