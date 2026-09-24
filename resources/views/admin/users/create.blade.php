@extends('layouts.app')
{{-- resources/views/admin/users/create.blade.php / resources/views/admin/users/edit.blade.php --}}

@php
    $isEdit = isset($user);
    $title = $isEdit ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد';
    $action = $isEdit ? route('admin.users.update', $user) : route('admin.users.store');
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        
        <form action="{{ $action }}" method="POST" class="p-8 space-y-8">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- Section 1: Basic Info --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-r-4 border-indigo-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">المعلومات الأساسية</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">الاسم الكامل <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required
                               class="w-full @error('name') border-rose-500 @enderror">
                        @error('name') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required
                               class="w-full @error('email') border-rose-500 @enderror">
                        @error('email') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-bold text-gray-700 mb-2">الدور في النظام <span class="text-rose-500">*</span></label>
                        <select name="role" id="role" required
                                class="w-full">
                            <option value="data_entry"      {{ old('role', $user->role ?? '') === 'data_entry' ? 'selected' : '' }}>مدخل بيانات</option>
                            <option value="project_manager" {{ old('role', $user->role ?? '') === 'project_manager' ? 'selected' : '' }}>مدير مشروع</option>
                            <option value="admin"           {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>مدير نظام</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Active Status (Hidden on create, shown on edit maybe?) --}}
                    @if(!$isEdit)
                        <input type="hidden" name="is_active" value="1">
                    @else
                        <div>
                            <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">حالة الحساب</label>
                            <select name="is_active" id="is_active"
                                    class="w-full">
                                <option value="1" {{ old('is_active', $user->is_active ?? '') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active', $user->is_active ?? '') == '0' ? 'selected' : '' }}>معطّل</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section 2: Security --}}
            <div class="space-y-6 pt-6 border-t border-gray-50">
                <div class="flex items-center gap-2 border-r-4 border-amber-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">الأمان</h3>
                </div>
                
                @if($isEdit)
                    <p class="text-xs text-amber-600 font-bold bg-amber-50 p-3 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        اترك حقول كلمة المرور فارغة إذا كنت لا تريد تغييره.
                    </p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور {{ $isEdit ? '' : '*' }}</label>
                        <input type="password" name="password" id="password" {{ $isEdit ? '' : 'required' }}
                               class="w-full @error('password') border-rose-500 @enderror">
                        @error('password') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" {{ $isEdit ? '' : 'required' }}
                               class="w-full">
                    </div>
                </div>
            </div>

            {{-- Section 3: Optional Profile Info --}}
            <div class="space-y-6 pt-6 border-t border-gray-50">
                <div class="flex items-center gap-2 border-r-4 border-emerald-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">البيانات الإضافية (اختياري)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                               class="w-full">
                    </div>
                    <div>
                        <label for="id_number" class="block text-sm font-bold text-gray-700 mb-2">رقم الهوية</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $user->id_number ?? '') }}"
                               class="w-full">
                    </div>
                    <div>
                        <label for="specialty" class="block text-sm font-bold text-gray-700 mb-2">التخصص</label>
                        <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $user->specialty ?? '') }}"
                               class="w-full">
                    </div>
                    <div>
                        <label for="governorate" class="block text-sm font-bold text-gray-700 mb-2">المحافظة</label>
                        <input type="text" name="governorate" id="governorate" value="{{ old('governorate', $user->governorate ?? '') }}"
                               class="w-full">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-bold text-gray-700 mb-2">العنوان</label>
                        <textarea name="address" id="address" rows="2"
                               class="w-full">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-6 flex items-center justify-end gap-4 border-t border-gray-50">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors px-4 py-2">إلغاء</a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                    {{ $isEdit ? 'حفظ التعديلات' : 'إضافة المستخدم' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
