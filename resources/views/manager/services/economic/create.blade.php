@extends('layouts.app')
{{-- resources/views/manager/services/economic/create.blade.php --}}

@section('title', 'مشروع تمكين جديد')
@section('page-title', 'إضافة هيكل مشروع تمكين')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <form action="{{ route('project_manager.services.economic_empowerment.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="space-y-6">
                <div class="flex items-center gap-2 border-r-4 border-indigo-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">بيانات المشروع (مدير المشروع)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم النشاط / المبادرة</label>
                        <input type="text" name="project_name" required placeholder="مثلاً: منحة تمكين النساء" class="w-full">
                    </div>

                    {{-- Parent Project Name --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع</label>
                        <input type="text" name="parent_project" placeholder="مثلاً: مشروع سبل العيش" class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">منسق المشروع</label>
                        <input type="text" name="coordinator_name" required class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ بداية المشروع</label>
                        <input type="date" name="start_date" required class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ نهاية المشروع</label>
                        <input type="date" name="end_date" required class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الجهة الممولة</label>
                        <input type="text" name="funder" required class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">القيمة الإجمالية للمنحة</label>
                        <input type="number" step="0.01" name="total_grant_value" required class="w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">حالة المشروع (الخدمة)</label>
                        <select name="status" class="w-full">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط (متاح للإدخال)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة (مخفي)</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل (مغلق)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع</label>
                        <select name="sector_type" class="w-full">
                            <option value="" disabled selected>اختر النوع...</option>
                            <option value="protection" {{ old('sector_type') == 'protection' ? 'selected' : '' }}>الحماية</option>
                            <option value="education" {{ old('sector_type') == 'education' ? 'selected' : '' }}>التعليم</option>
                            <option value="health" {{ old('sector_type') == 'health' ? 'selected' : '' }}>الصحة</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">وصف المشروع</label>
                    <textarea name="project_description" rows="3"
                              class="w-full">{{ old('project_description') }}</textarea>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end gap-3 border-t border-gray-50">
                <a href="{{ route('project_manager.services.economic_empowerment.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">إلغاء</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-2xl font-bold font-arabic">إنشاء قسيمة المشروع</button>
            </div>
        </form>
    </div>
</div>
@endsection
