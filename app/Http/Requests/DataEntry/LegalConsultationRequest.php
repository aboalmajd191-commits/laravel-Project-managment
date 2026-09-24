<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class LegalConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'full_name'           => 'required|string|max:255',
            'name_en'              => 'nullable|string|max:255',
            'id_number'           => 'required|string|max:20',
            'birth_date'          => 'required|date',
            'marital_status'      => 'required|string|max:100',
            'gender'              => 'required|string|max:20',
            'phone'               => 'required|string|max:50',
            'current_address'     => 'required|string|max:255',
            'previous_address'    => 'required|string|max:255',
            'displacement_count'  => 'required|integer|min:0',
            'disability_type'      => 'nullable|string|max:255',
            'source'              => 'required|string|max:255',
            'problem_description' => 'required|string',
            'legal_aid_details'    => 'required|string',
            'description'         => 'nullable|string',
            'project_description' => 'nullable|string',
            'attachments'         => 'nullable|array',
            'attachments.*'       => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
        ];
    }
}
