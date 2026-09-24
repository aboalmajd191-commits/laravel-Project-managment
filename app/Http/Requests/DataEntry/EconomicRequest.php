<?php

// app/Http/Requests/DataEntry/EconomicRequest.php

declare(strict_types=1);

namespace App\Http\Requests\DataEntry;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class EconomicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isStore = $this->isMethod('POST');

        return [
            'project_id'        => [
                $isStore ? 'required' : 'sometimes',
                'exists:projects,id',
                function ($attribute, $value, $fail) {
                    $project = Project::find($value);
                    if ($project && $project->status !== 'active') {
                        $fail('لا يمكن إضافة مدخلات لمشروع غير نشط.');
                    }
                },
            ],
            'project_name'       => ($isStore ? 'required' : 'sometimes') . '|string|max:255',
            'coordinator_name'   => ($isStore ? 'required' : 'sometimes') . '|string|max:255',
            'start_date'         => ($isStore ? 'required' : 'sometimes') . '|date',
            'end_date'           => ($isStore ? 'required' : 'sometimes') . '|date|after_or_equal:start_date',
            'funder'             => ($isStore ? 'required' : 'sometimes') . '|string|max:255',
            'total_grant_value'  => ($isStore ? 'required' : 'sometimes') . '|numeric|min:0',

            // Data Entry Fields (Details)
            'owner_name'         => 'sometimes|required|string|max:255',
            'name_en'            => 'nullable|string|max:255',
            'birth_date'         => 'nullable|date',
            'grant_date'         => 'nullable|date',
            'id_number'          => 'sometimes|required|string|max:20',
            'grant_value'        => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $project = $this->route('economic_empowerment');
                    if ($project) {
                        // If project has parent_id, it's a child being edited. If not, it's the parent project we're adding to.
                        $parent = $project->parent_id ? $project->parent : $project;
                        
                        if ($parent) {
                            $totalBudget = (float) $parent->total_grant_value;
                            
                            // Calculate used budget (approved children)
                            // If we're editing a child, exclude current child from the sum
                            $usedBudget = $parent->children()
                                ->where('approval_status', 'approved')
                                ->when($project->parent_id, function($q) use ($project) {
                                    $q->where('id', '!=', $project->id);
                                })
                                ->sum('grant_value');
                                
                            $remaining = $totalBudget - $usedBudget;
                            
                            if ((float)$value > (float)$remaining) {
                                $fail("قيمة المنحة تتجاوز المتبقي من ميزانية المشروع. المتبقي حالياً: " . number_format($remaining, 2));
                            }
                        }
                    }
                }
            ],
            'individuals_count'  => 'sometimes|required|integer|min:1',
            'phone'              => 'sometimes|required|string|max:20',
            'governorate'        => 'sometimes|required|string|max:100',
            'marital_status'     => 'nullable|string|max:50',
            'education_level'    => 'nullable|string|max:100',
            'address'            => 'nullable|string',
            'description'        => 'nullable|string',
            'project_description' => 'nullable|string',
            'disability_type'    => 'nullable|string',
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,xlsx,xls,pdf|max:10240',
            'images'             => 'nullable|array|max:10',
            'images.*'           => 'file|image|max:5120|mimes:jpeg,png,jpg,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required'      => 'المشروع الممول مطلوب',
            'owner_name.required'      => 'اسم صاحب المشروع مطلوب',
            'owner_id_number.required' => 'رقم هويّة صاحب المشروع مطلوب',
            'project_name.required'    => 'اسم المشروع الصغير مطلوب',
            'total_cost.required'      => 'التكلفة الإجمالية مطلوبة',
            'individuals_count.required' => 'عدد أفراد الأسرة مطلوب',
            'phone.required'           => 'رقم الجوال مطلوب',
            'governorate.required'     => 'المحافظة مطلوبة',
        ];
    }
}
