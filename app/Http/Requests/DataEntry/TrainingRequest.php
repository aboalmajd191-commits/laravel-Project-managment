<?php

// app/Http/Requests/DataEntry/TrainingRequest.php

declare(strict_types=1);

namespace App\Http\Requests\DataEntry;

use Illuminate\Foundation\Http\FormRequest;

class TrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // We handle role check in controller/middleware
    }

    public function rules(): array
    {
        $isStore = $this->isMethod('POST');
        
        return [
            'name'              => 'sometimes|string|max:255',
            'start_date'        => 'sometimes|date',
            'end_date'          => 'sometimes|date|after_or_equal:start_date',
            'funder'            => 'sometimes|string|max:255',
            'beneficiary_count' => 'sometimes|integer|min:1',
            
            'gender_type'       => 'sometimes|required|in:male,female,both',
            
            // Attendees validation (Only required if we are filling details)
            'attendees'               => 'sometimes|array',
            'attendees.*.name'        => 'nullable|string|max:255',
            'attendees.*.name_en'     => 'nullable|string|max:255',
            'attendees.*.id_number'   => 'nullable|string|max:20',
            'attendees.*.specialty'   => 'nullable|string|max:100',
            'attendees.*.birth_date'  => 'nullable|date',
            'attendees.*.phone'       => 'nullable|string|max:20',
            'attendees.*.governorate' => 'nullable|string|max:50',
            'attendees.*.marital_status' => 'nullable|string',
            'attendees.*.disability_type' => 'nullable|string',
            'notes'             => 'nullable|string',
            'description'       => 'nullable|string',
            'activity_name'     => 'nullable|string|max:255',
            'governorate'       => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'عنوان التدريب مطلوب',
            'start_date.required'        => 'تاريخ البدء مطلوب',
            'end_date.required'          => 'تاريخ الانتهاء مطلوب',
            'funder.required'            => 'الجهة الممولة مطلوبة',
            'beneficiary_count.required' => 'عدد المستفيدين مطلوب',
        ];
    }
}
