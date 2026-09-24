<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class GroupSupportSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'session_name'        => 'required|string|max:255',
            'location'            => 'required|string|max:255',
            'duration'            => 'required|string|max:100',
            'session_number'      => 'required|string|max:50',
            'attendees_count'     => 'required|integer|min:0',
            'session_leader'      => 'required|string|max:255',
            'hosting_entity'      => 'required|string|max:255',
            'session_facilitator' => 'required|string|max:255',
            'description'         => 'nullable|string',
            'project_description' => 'nullable|string',
            'attachments'         => 'nullable|array',
            'attachments.*'       => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
            
            'attendees' => 'required|array|min:1',
            'attendees.*.beneficiary_name' => 'required|string|max:255',
            'attendees.*.name_en'          => 'nullable|string|max:255',
            'attendees.*.id_number'        => 'required|string|max:20',
            'attendees.*.mobile'           => 'required|string|max:50',
            'attendees.*.gender'           => 'required|string|max:20',
            'attendees.*.birth_date'       => 'required|date',
            'attendees.*.governorate'      => 'required|string|max:255',
            'attendees.*.marital_status'   => 'required|string|max:100',
            'attendees.*.health_status'    => 'required|string|max:255',
            'attendees.*.disability_type'  => 'nullable|string|max:255',
            'attendees.*.displacement_count' => 'required|integer|min:0',
        ];
    }
}
