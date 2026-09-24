<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class PsychologicalConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'full_name'             => 'required|string|max:255',
            'name_en'               => 'nullable|string|max:255',
            'case_code'             => 'required|string|max:100',
            'id_number'             => 'required|string|max:20',
            'birth_date'            => 'required|date',
            'marital_status'        => 'required|string|max:100',
            'mobile'                => 'required|string|max:50',
            'education_level'       => 'required|string|max:255',
            'mission'               => 'required|string|max:255',
            'displacement_status'   => 'required|string|max:100',
            'original_governorate'  => 'required|string|max:255',
            'primary_address'       => 'required|string|max:255',
            'displacement_address'  => 'required|string|max:255',
            'health_status'         => 'required|string|max:255',
            'disability_type'       => 'nullable|string|max:255',
            'case_description'      => 'required|string',
            'description'           => 'nullable|string',
            'project_description'   => 'nullable|string',
            'procedure_guidance'    => 'required|string',
            'recommendations'       => 'required|string',
            'attachments'           => 'nullable|array',
            'attachments.*'         => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
        ];
    }
}
