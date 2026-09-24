<?php

declare(strict_types=1);

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class EconomicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isProjectManager() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        if ($this->has('tab') && $this->tab === 'entry') {
            return [
                'owner_name'         => 'nullable|string|max:255',
                'id_number'          => 'nullable|string|max:20',
                'phone'              => 'nullable|string|max:20',
                'grant_value'        => 'nullable|numeric|min:0',
                'individuals_count'  => 'nullable|integer|min:1',
                'governorate'        => 'nullable|string|max:100',
                'grant_date'         => 'nullable|date',
                'marital_status'     => 'nullable|string|max:50',
                'education_level'    => 'nullable|string|max:100',
                'images.*'           => 'nullable|image|max:2048',
            ];
        }

        return [
            'project_name'        => 'required|string|max:255',
            'parent_project'      => 'nullable|string|max:255',
            'coordinator_name'    => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'funder'              => 'required|string|max:255',
            'total_grant_value'   => 'required|numeric|min:0',
            'status'              => 'required|in:draft,active,completed',
            'sector_type'         => 'required|in:protection,education,health',
            'project_description' => 'nullable|string',
        ];
    }
}
