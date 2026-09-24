<?php

// app/Models/EconomicProjectImage.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EconomicProjectImage extends Model
{
    // لا SoftDeletes — الصور تُحذف فعلياً مع المشروع

    protected $fillable = [
        'economic_project_id',
        'path',
        'original_name',
        'uploaded_by',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * حذف الملف الفعلي من القرص عند حذف السجل.
     * نستخدم static deleting بدلاً من Observer لبساطة الكود.
     */
    protected static function booted(): void
    {
        static::deleting(function (EconomicProjectImage $image) {
            Storage::disk('private')->delete($image->path);
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /** المشروع الاقتصادي الأم */
    public function economicProject(): BelongsTo
    {
        return $this->belongsTo(EconomicProject::class);
    }

    /** من رفع الصورة */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
