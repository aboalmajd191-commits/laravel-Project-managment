@extends('layouts.app')
{{-- resources/views/manager/projects/create.blade.php / edit.blade.php --}}

@php
    $isEdit = isset($project);
    $title = $isEdit ? 'تعديل المشروع' : 'إنشاء مشروع جديد';
    $action = $isEdit ? route('project_manager.projects.update', $project) : route('project_manager.projects.store');
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Main Column --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Basic Info Card --}}
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-r-4 border-indigo-500 pr-4">
                        <h3 class="text-lg font-bold text-gray-800">تفاصيل المشروع</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name ?? '') }}" required
                                   class="w-full @error('name') border-rose-500 @enderror">
                            @error('name') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">وصف المشروع</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="service_type" class="block text-sm font-bold text-gray-700 mb-2">نوع الخدمة <span class="text-rose-500">*</span></label>
                            <select name="service_type" id="service_type" required
                                    class="w-full">
                                <option value="training" {{ old('service_type', $project->service_type ?? '') === 'training' ? 'selected' : '' }}>تدريب وبناء قدرات</option>
                                <option value="awareness_workshop" {{ old('service_type', $project->service_type ?? '') === 'awareness_workshop' ? 'selected' : '' }}>ورش توعوية</option>
                                <option value="economic_empowerment" {{ old('service_type', $project->service_type ?? '') === 'economic_empowerment' ? 'selected' : '' }}>التمكين الاقتصادي</option>
                                <option value="cash_assistance" {{ old('service_type', $project->service_type ?? '') === 'cash_assistance' ? 'selected' : '' }}>مساعدات نقدية</option>
                                <option value="in_kind_assistance" {{ old('service_type', $project->service_type ?? '') === 'in_kind_assistance' ? 'selected' : '' }}>مساعدات عينية</option>
                                <option value="community_initiatives" {{ old('service_type', $project->service_type ?? '') === 'community_initiatives' ? 'selected' : '' }}>مبادرات مجتمعية</option>
                                <option value="support_sponsorship" {{ old('service_type', $project->service_type ?? '') === 'support_sponsorship' ? 'selected' : '' }}>دعم ورعاية ايتام</option>
                            </select>
                        </div>
                        <div>
                            <label for="sector_type" class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع <span class="text-rose-500">*</span></label>
                            <select name="sector_type" id="sector_type" required
                                    class="w-full">
                                <option value="" disabled {{ old('sector_type', $project->sector_type ?? '') === '' ? 'selected' : '' }}>اختر القطاع...</option>
                                <option value="protection" {{ old('sector_type', $project->sector_type ?? '') === 'protection' ? 'selected' : '' }}>الحماية</option>
                                <option value="education" {{ old('sector_type', $project->sector_type ?? '') === 'education' ? 'selected' : '' }}>التعليم</option>
                                <option value="health" {{ old('sector_type', $project->sector_type ?? '') === 'health' ? 'selected' : '' }}>الصحة</option>
                            </select>
                            @error('sector_type') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
                        <div>
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">حالة المشروع <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required
                                    class="w-full">
                                <option value="active" {{ old('status', $project->status ?? '') === 'active' ? 'selected' : '' }}>جاري العمل</option>
                                <option value="completed" {{ old('status', $project->status ?? '') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ old('status', $project->status ?? '') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- Date Range Card --}}
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-r-4 border-emerald-500 pr-4">
                        <h3 class="text-lg font-bold text-gray-800">النطاق الزمني</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-bold text-gray-700 mb-2">تاريخ البدء</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $isEdit && $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                                   class="w-full">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-bold text-gray-700 mb-2">تاريخ الانتهاء المتوقع</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $isEdit && $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                                   class="w-full">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column: Assignments --}}
            <div class="space-y-6">
                <div class="bg-slate-900 p-8 rounded-2xl text-white shadow-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold">تعيين مدخلي البيانات</h3>
                    </div>

                    <p class="text-slate-400 text-xs mb-6 leading-relaxed">
                        اختر الموظفين المسموح لهم بإدخال البيانات لهذا المشروع. سيظهر المشروع فقط للموظفين المختارين.
                    </p>

                    <div class="space-y-3 max-h-80 overflow-y-auto sidebar-scroll pr-2 pl-2">
                        @foreach($dataEntryUsers as $staff)
                            <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors cursor-pointer group">
                                <input type="checkbox" name="assigned_users[]" value="{{ $staff->id }}" 
                                       class="rounded text-indigo-500 focus:ring-indigo-500 bg-white/10 border-white/20"
                                       {{ in_array($staff->id, old('assigned_users', $assignedUserIds ?? [])) ? 'checked' : '' }}>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold truncate">{{ $staff->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ $staff->email }}</p>
                                </div>
                            </label>
                        @endforeach
                        @if($dataEntryUsers->isEmpty())
                            <p class="text-xs text-slate-500 text-center py-4">لا يوجد مدخلي بيانات نشطين</p>
                        @endif
                    </div>
                </div>

                {{-- Action Card --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-3">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                        {{ $isEdit ? 'حفظ التغييرات' : 'إنشاء المشروع' }}
                    </button>
                    <a href="{{ route('project_manager.projects.index') }}" 
                       class="w-full text-center py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                        إلغاء والعودة
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
