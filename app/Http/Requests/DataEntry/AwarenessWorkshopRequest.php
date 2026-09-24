<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class AwarenessWorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'meeting_name'        => 'required|string|max:255',
            'meeting_location'    => 'required|string|max:255',
            'meeting_duration'    => 'required|string|max:100',
            'session_number'      => 'required|string|max:50',
            'attendance_count'    => 'required|integer|min:0',
            'meeting_moderator'   => 'required|string|max:255',
            'hosting_party'       => 'required|string|max:255',
            'session_facilitator' => 'required|string|max:255',
            'description'         => 'nullable|string',
            'project_description' => 'nullable|string',
            'attachments'         => 'nullable|array',
            'attachments.*'       => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
            'attendees'           => 'nullable|array',
            'attendees.*.name'    => 'nullable|string|max:255',
            'attendees.*.name_en' => 'nullable|string|max:255',
            'attendees.*.id_number' => 'nullable|string|max:20',
            'attendees.*.specialty' => 'nullable|string|max:255',
            'attendees.*.birth_date' => 'nullable|date',
            'attendees.*.phone'   => 'nullable|string|max:20',
            'attendees.*.governorate' => 'nullable|string|max:100',
            'attendees.*.marital_status' => 'nullable|string|max:255',
            'attendees.*.disability_type' => 'nullable|string|max:255',
        ];
    }
}
