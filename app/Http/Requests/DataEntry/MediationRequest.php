<?php

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class MediationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'beneficiary_name'  => 'required|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'birth_date'        => 'nullable|date',
            'id_number'         => 'required|string|max:20',
            'region'            => 'nullable|string|max:255',
            'mobile'            => 'nullable|string|max:50',
            'disability_type'    => 'nullable|string|max:255',
            'marital_status'    => 'nullable|string|max:100',
            'individuals_count' => 'nullable|integer|min:0',
            'health_status'     => 'nullable|string|max:255',
            'cases_count'       => 'nullable|integer|min:0',
            'mediation_type'    => 'nullable|string|max:100', // شرعي , نظامي
            'extraction_date'   => 'nullable|date',
            'amount'            => 'nullable|numeric|min:0',
            'description'       => 'nullable|string',
            'project_description' => 'nullable|string',
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
        ];
    }
}
