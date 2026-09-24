@extends('layouts.app')
{{-- resources/views/manager/services/training/create.blade.php --}}

@section('title', 'إنشاء تدريب جديد')
@section('page-title', 'إضافة هيكل تدريب جديد')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <form action="{{ route('project_manager.services.training.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="space-y-6">
                <div class="flex items-center gap-2 border-r-4 border-indigo-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">بيانات النشاط (مدير المشروع)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Activity Name --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم التدريب / النشاط</label>
                        <input type="text" name="name" required placeholder="مثلاً: دورة الحاسوب الأساسية"
                               class="w-full">
                    </div>

                    {{-- Parent Project Name --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع</label>
                        <input type="text" name="parent_project" placeholder="مثلاً: مشروع تمكين الشباب"
                               class="w-full">
                    </div>

                    {{-- Beneficiary Count --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">عدد المستفيدين المستهدف</label>
                        <input type="number" name="beneficiary_count" required min="1"
                               class="w-full">
                    </div>

                    {{-- Funder --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الجهة الممولة</label>
                        <input type="text" name="funder" required placeholder="اسم الممول"
                               class="w-full">
                    </div>

                    {{-- Dates --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البدء</label>
                        <input type="date" name="start_date" required
                               class="w-full">
                    </div>
                    <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الانتهاء</label>
                            <input type="date" name="end_date" required
                                   class="w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">حالة المشروع (الخدمة)</label>
                            <select name="status" class="w-full">
                                <option value="active">نشط (متاح للإدخال)</option>
                                <option value="draft">مسودة (مخفي)</option>
                                <option value="completed">مكتمل (مغلق)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع</label>
                            <select name="sector_type" class="w-full">
                                <option value="" disabled selected>اختر النوع...</option>
                                <option value="protection">الحماية</option>
                                <option value="education">التعليم</option>
                                <option value="health">الصحة</option>
                            </select>
                        </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end gap-3 border-t border-gray-50">
                <a href="{{ route('project_manager.services.training.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">إلغاء</a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                    إنشاء النشاط وحفظه
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
