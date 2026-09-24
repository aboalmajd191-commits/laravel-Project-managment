<?php

// app/Models/Attendee.php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasServiceAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    use HasServiceAttachments;

    protected $fillable = [
        'training_id',
        'awareness_workshop_id',
        'name',
        'name_en',
        'id_number',
        'specialty',
        'birth_date',
        'phone',
        'governorate',
        'marital_status',
        'disability_type',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** التدريب الذي حضره هذا الشخص */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    /** الورشة التي حضرها هذا الشخص */
    public function awarenessWorkshop(): BelongsTo
    {
        return $this->belongsTo(AwarenessWorkshop::class, 'awareness_workshop_id');
    }
}
