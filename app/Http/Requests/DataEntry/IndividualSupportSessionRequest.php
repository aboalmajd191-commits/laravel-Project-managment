<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class IndividualSupportSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'specialist'       => 'required|string|max:255',
            'beneficiary_name' => 'required|string|max:255',
            'name_en'          => 'nullable|string|max:255',
            'case_code'        => 'required|string|max:100',
            'birth_date'       => 'required|date',
            'mobile'           => 'required|string|max:50',
            'address'          => 'required|string|max:255',
            'education_level'  => 'required|string|max:255',
            'marital_status'   => 'required|string|max:255',
            'disability_type'  => 'nullable|string|max:255',
            'main_complaint'   => 'required|string',
            'description'      => 'nullable|string',
            'attachments'      => 'nullable|array',
            'attachments.*'    => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
            
            // Sessions validation
            'sessions' => 'required|array|min:1',
            'sessions.*.session_number'       => 'required|string|max:50',
            'sessions.*.session_date'         => 'required|date',
            'sessions.*.session_time'         => 'required|string|max:50',
            'sessions.*.intervention_file'    => 'required|string',
            'sessions.*.intervention_summary' => 'required|string',
        ];
    }
}
