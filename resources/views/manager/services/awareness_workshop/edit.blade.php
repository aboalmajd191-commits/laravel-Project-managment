@extends('layouts.app')

@section('title', 'إدارة الورشة التوعوية')
@section('page-title', 'سجل الورش التوعوية (إدارة المشروع)')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium" dir="rtl">

    {{-- Success Banner --}}
    @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-100 rounded-2xl px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-500 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-100 flex items-center justify-between bg-gray-50/60 px-6">
            <div class="flex items-center gap-1">
                <a href="{{ route('project_manager.services.awareness_workshop.edit', $awareness_workshop) }}"
                   class="px-5 py-4 text-sm font-bold border-b-2 transition-all {{ request('tab') !== 'entry' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    ⚙️ البيانات الأساسية
                </a>
                <a href="{{ route('project_manager.services.awareness_workshop.edit', [$awareness_workshop, 'tab' => 'entry']) }}"
                   class="px-5 py-4 text-sm font-bold border-b-2 transition-all {{ request('tab') === 'entry' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    ➕ إضافة جلسة
                    @if($awareness_workshop->children_count > 0)
                        <span class="mr-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600">
                            {{ $awareness_workshop->children_count }}
                        </span>
                    @endif
                </a>
            </div>
            <a href="{{ route('project_manager.services.awareness_workshop.index') }}"
               class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                العودة للسجل
            </a>
        </div>

        {{-- ─── TAB: Basic Data ─── --}}
        @if(request('tab') !== 'entry')
        <form action="{{ route('project_manager.services.awareness_workshop.update', $awareness_workshop) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <input type="hidden" name="tab" value="basic">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع / النشاط <span class="text-rose-500">*</span></label>
                    <input type="text" name="project_name" value="{{ old('project_name', $awareness_workshop->project_name) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الجهة الممولة <span class="text-rose-500">*</span></label>
                    <input type="text" name="funder" value="{{ old('funder', $awareness_workshop->funder) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البدء <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $awareness_workshop->start_date?->format('Y-m-d')) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الانتهاء <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date', $awareness_workshop->end_date?->format('Y-m-d')) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">إجمالي المستفيدين <span class="text-rose-500">*</span></label>
                    <input type="number" name="total_beneficiaries" value="{{ old('total_beneficiaries', $awareness_workshop->total_beneficiaries) }}" required min="0"
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">حالة النشاط <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full">
                        <option value="active"    {{ old('status', $awareness_workshop->status) == 'active'    ? 'selected' : '' }}>نشط</option>
                        <option value="draft"     {{ old('status', $awareness_workshop->status) == 'draft'     ? 'selected' : '' }}>مسودة</option>
                        <option value="completed" {{ old('status', $awareness_workshop->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع <span class="text-rose-500">*</span></label>
                    <select name="sector_type" required class="w-full">
                        <option value="" disabled {{ !$awareness_workshop->sector_type ? 'selected' : '' }}>اختر النوع...</option>
                        <option value="protection" {{ old('sector_type', $awareness_workshop->sector_type) == 'protection' ? 'selected' : '' }}>الحماية</option>
                        <option value="education"   {{ old('sector_type', $awareness_workshop->sector_type) == 'education' ? 'selected' : '' }}>التعليم</option>
                        <option value="health"      {{ old('sector_type', $awareness_workshop->sector_type) == 'health' ? 'selected' : '' }}>الصحة</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ البيانات الأساسية
                </button>
            </div>
        </form>

        {{-- Sessions List under basic tab --}}
        @if($awareness_workshop->children->isNotEmpty())
        <div class="border-t border-gray-50 px-6 pb-6">
            <h4 class="text-sm font-bold text-gray-700 mt-6 mb-4">الجلسات المُدخَلة ({{ $awareness_workshop->children->count() }})</h4>
            <div class="space-y-3">
                @foreach($awareness_workshop->children as $child)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xs font-bold flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $child->meeting_name ?: 'جلسة '.$loop->iteration }}</p>
                            <p class="text-[11px] text-gray-400">{{ $child->meeting_location ?: '' }} — بواسطة: {{ $child->submitter?->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full
                            {{ $child->approval_status === 'approved' ? 'text-emerald-700 bg-emerald-50 border border-emerald-100' : 'text-amber-700 bg-amber-50 border border-amber-100' }}">
                            {{ $child->approval_status === 'approved' ? 'معتمد' : 'قيد المراجعة' }}
                        </span>
                        <span class="text-[10px] text-gray-400">{{ $child->created_at->format('Y/m/d') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ─── TAB: Entry Data ─── --}}
        @else
        <form action="{{ route('project_manager.services.awareness_workshop.update', $awareness_workshop) }}" method="POST" class="p-6 space-y-6">
            @csrf @method('PUT')
            <input type="hidden" name="tab" value="entry">

            {{-- Context Summary --}}
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-emerald-600 font-bold">إضافة جلسة لـ: {{ $awareness_workshop->project_name }}</p>
                    <p class="text-[11px] text-emerald-500">الممول: {{ $awareness_workshop->funder }} — عدد الجلسات الحالية: {{ $awareness_workshop->children_count }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم اللقاء</label>
                    <input type="text" name="meeting_name" value="{{ old('meeting_name') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">مكان اللقاء</label>
                    <input type="text" name="meeting_location" value="{{ old('meeting_location') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">مدة اللقاء</label>
                    <input type="text" name="meeting_duration" value="{{ old('meeting_duration') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجلسة</label>
                    <input type="text" name="session_number" value="{{ old('session_number') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">عدد الحضور</label>
                    <input type="number" name="attendance_count" value="{{ old('attendance_count') }}" min="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">مسير اللقاء</label>
                    <input type="text" name="meeting_moderator" value="{{ old('meeting_moderator') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الجهة المستضيفة</label>
                    <input type="text" name="hosting_party" value="{{ old('hosting_party') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ميسر الجلسة</label>
                    <input type="text" name="session_facilitator" value="{{ old('session_facilitator') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-600/20">
                    💾 حفظ الجلسة وإضافة أخرى
                </button>
                <a href="{{ route('project_manager.services.awareness_workshop.index') }}"
                   class="text-sm text-gray-400 hover:text-gray-600 px-4 font-bold">
                    الانتهاء
                </a>
            </div>
        </form>
        @endif

    </div>
</div>
@endsection
