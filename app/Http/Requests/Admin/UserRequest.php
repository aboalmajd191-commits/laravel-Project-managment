<?php

// app/Http/Requests/Admin/UserRequest.php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name'        => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password'    => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'role'        => ['required', Rule::in(['admin', 'project_manager', 'data_entry'])],
            'is_active'   => 'boolean',
            'phone'       => 'nullable|string|max:20',
            'id_number'   => 'nullable|string|max:20',
            'specialty'   => 'nullable|string|max:255',
            'governorate' => 'nullable|string|max:100',
            'address'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'الاسم مطلوب',
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.unique'      => 'هذا البريد الإلكتروني مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب أن لا تقل عن 8 أحرف',
            'password.confirmed'=> 'تأكيد كلمة المرور غير متطابق',
            'role.required'     => 'الدور مطلوب',
        ];
    }
}
