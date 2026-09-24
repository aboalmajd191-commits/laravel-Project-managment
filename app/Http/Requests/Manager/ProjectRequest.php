<?php

// app/Http/Requests/Manager/ProjectRequest.php

declare(strict_types=1);

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isProjectManager() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'service_type' => ['required', Rule::in([
                'training',
                'awareness_workshop',
                'economic_empowerment',
                'cash_assistance',
                'in_kind_assistance',
                'community_initiatives',
                'support_sponsorship',
            ])],

            'sector_type'  => ['required', Rule::in(['protection', 'education', 'health'])],
            'status'       => ['required', Rule::in(['active', 'completed', 'cancelled'])],
            'start_date'   => 'nullable|date|before_or_equal:end_date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'assigned_users'=> 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'اسم المشروع مطلوب',
            'service_type.required' => 'يجب اختيار نوع الخدمة',
            'sector_type.required'  => 'يجب اختيار نوع القطاع',
            'status.required'       => 'حالة المشروع مطلوبة',
        ];
    }
}
