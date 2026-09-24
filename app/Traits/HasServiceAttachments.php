<?php

namespace App\Traits;

use App\Models\ServiceAttachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasServiceAttachments
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(ServiceAttachment::class, 'attachable');
    }

    /**
     * Handle uploading multiple attachments from a Request.
     */
    public function handleAttachments($request, $fieldName = 'attachments'): void
    {
        if ($request->hasFile($fieldName)) {
            $this->uploadAttachments($request->file($fieldName));
        }
    }

    /**
     * Handle uploading an array of files.
     */
    public function uploadAttachments(array $files): void
    {
        foreach ($files as $file) {
            if ($file->isValid()) {
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getClientOriginalExtension();
                // Store in private disk as per CLAUDE.md
                $path = $file->store('attachments/' . $this->getTable(), 'private');

                $this->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'file_type' => $fileType,
                ]);
            }
        }
    }
}
