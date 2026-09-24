@extends('layouts.app')
{{-- resources/views/manager/services/training/edit.blade.php --}}

@section('title', 'تعديل بيانات التدريب')
@section('page-title', 'تحديث بيانات النشاط والحضور')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <form action="{{ route('project_manager.services.training.update', $training) }}" method="POST" class="p-8 space-y-10">
            @csrf @method('PUT')
            <input type="hidden" name="tab" value="{{ request('tab') }}">

            @if(request('tab') !== 'entry')
                {{-- 1. Manager Fields --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-r-4 border-indigo-500 pr-4">
                    <h3 class="text-lg font-bold text-gray-800">البيانات الأساسية (تعديل المدير)</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-80 hover:opacity-100 transition-opacity">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">اسم التدريب</label>
                        <input type="text" name="name" value="{{ $training->name }}" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">اسم المشروع</label>
                        <input type="text" name="parent_project" value="{{ $training->parent_project }}" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">الممول</label>
                        <input type="text" name="funder" value="{{ $training->funder }}" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">العدد المستهدف</label>
                        <input type="number" name="beneficiary_count" value="{{ $training->beneficiary_count }}" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">تاريخ البدء</label>
                        <input type="date" name="start_date" value="{{ $training->start_date->format('Y-m-d') }}" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">تاريخ الانتهاء</label>
                        <input type="date" name="end_date" value="{{ $training->end_date->format('Y-m-d') }}" class="w-full">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">حالة المشروع (الخدمة)</label>
                        <select name="status" class="w-full">
                            <option value="active" {{ $training->status == 'active' ? 'selected' : '' }}>نشط (متاح للإدخال)</option>
                            <option value="draft" {{ $training->status == 'draft' ? 'selected' : '' }}>مسودة (مخفي)</option>
                            <option value="completed" {{ $training->status == 'completed' ? 'selected' : '' }}>مكتمل (مغلق)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">نوع القطاع</label>
                        <select name="sector_type" class="w-full">
                            <option value="" disabled {{ !$training->sector_type ? 'selected' : '' }}>اختر النوع...</option>
                            <option value="protection" {{ $training->sector_type == 'protection' ? 'selected' : '' }}>الحماية</option>
                            <option value="education" {{ $training->sector_type == 'education' ? 'selected' : '' }}>التعليم</option>
                            <option value="health" {{ $training->sector_type == 'health' ? 'selected' : '' }}>الصحة</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            @if(request('tab') === 'entry')
                {{-- 2. Data Entry Fields (The core filling area) --}}
            @include('entry.services.training.partials.form_details', ['training' => $training])
            @endif

            <div class="pt-8 flex items-center justify-end gap-3 border-t border-gray-50">
                <a href="{{ route('project_manager.services.training.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">رجوع</a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
