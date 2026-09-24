@extends('layouts.app')

@section('title', 'إنشاء خدمة استشارات قانونية')
@section('page-title', 'إدارة خدمات الاستشارات القانونية')

@section('content')
<div class="max-w-4xl mx-auto text-right font-medium" dir="rtl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">بيانات الخدمة (مدير المشروع)</h3>
            <a href="{{ route('project_manager.services.legal_consultation.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                العودة
            </a>
        </div>
        
        <form action="{{ route('project_manager.services.legal_consultation.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع <span class="text-rose-500">*</span></label>
                    <input type="text" name="project_name" value="{{ old('project_name') }}" required
                           class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الجهة الممولة <span class="text-rose-500">*</span></label>
                    <input type="text" name="funder" value="{{ old('funder') }}" required
                           class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البدء <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الانتهاء <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الحالة <span class="text-rose-500">*</span></label>
                    <select name="status" required
                            class="w-full">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع <span class="text-rose-500">*</span></label>
                    <select name="sector_type" required
                            class="w-full">
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

            <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/20">
                    حفظ ونشر الخدمة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
