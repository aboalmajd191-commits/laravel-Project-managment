@extends('layouts.app')

@section('title', 'تعديل جلسات الدعم الجماعية')
@section('page-title', 'تحديث سجل جلسات الدعم الجماعية')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium" dir="rtl">

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
                <a href="{{ route('project_manager.services.group_support_session.edit', $group_support_session) }}"
                   class="px-5 py-4 text-sm font-bold border-b-2 transition-all {{ request('tab') !== 'entry' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    ⚙️ البيانات الأساسية
                </a>
                <a href="{{ route('project_manager.services.group_support_session.edit', [$group_support_session, 'tab' => 'entry']) }}"
                   class="px-5 py-4 text-sm font-bold border-b-2 transition-all {{ request('tab') === 'entry' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    ➕ إضافة جلسة جديدة
                    @if($group_support_session->children_count > 0)
                        <span class="mr-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600">
                            {{ $group_support_session->children_count }}
                        </span>
                    @endif
                </a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('project_manager.services.group_support_session.show', $group_support_session) }}"
                   class="text-sm text-indigo-600 hover:text-indigo-700 font-bold transition-all flex items-center gap-1">
                   عرض التفاصيل
                </a>
                <a href="{{ route('project_manager.services.group_support_session.index') }}"
                   class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    العودة للسجل
                </a>
            </div>
        </div>

        {{-- ─── TAB: Basic Data ─── --}}
        @if(request('tab') !== 'entry')
        <form action="{{ route('project_manager.services.group_support_session.update', $group_support_session) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <input type="hidden" name="tab" value="basic">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">جهة التمويل <span class="text-rose-500">*</span></label>
                    <input type="text" name="funder" value="{{ old('funder', $group_support_session->funder) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم المشروع <span class="text-rose-500">*</span></label>
                    <input type="text" name="project_name" value="{{ old('project_name', $group_support_session->project_name) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">بدء المشروع <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $group_support_session->start_date?->format('Y-m-d')) }}" required
                           class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نهاية المشروع <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date', $group_support_session->end_date?->format('Y-m-d')) }}" required
                           class="w-full">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">حالة النشاط <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full">
                            <option value="active"    {{ old('status', $group_support_session->status) == 'active'    ? 'selected' : '' }}>نشط (متاح للإدخال)</option>
                            <option value="draft"     {{ old('status', $group_support_session->status) == 'draft'     ? 'selected' : '' }}>مسودة</option>
                            <option value="completed" {{ old('status', $group_support_session->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">نوع القطاع <span class="text-rose-500">*</span></label>
                        <select name="sector_type" required class="w-full">
                            <option value="" disabled {{ !$group_support_session->sector_type ? 'selected' : '' }}>اختر النوع...</option>
                            <option value="protection" {{ old('sector_type', $group_support_session->sector_type) == 'protection' ? 'selected' : '' }}>الحماية</option>
                            <option value="education"   {{ old('sector_type', $group_support_session->sector_type) == 'education' ? 'selected' : '' }}>التعليم</option>
                            <option value="health"      {{ old('sector_type', $group_support_session->sector_type) == 'health' ? 'selected' : '' }}>الصحة</option>
                        </select>
                    </div>
                </div>
            </div>

                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">وصف المشروع</label>
                    <textarea name="project_description" rows="3"
                              class="w-full">{{ old('project_description', $group_support_session->project_description) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-50">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ البيانات الأساسية
                </button>
            </div>
        </form>

        {{-- Entries list --}}
        @if($group_support_session->children->isNotEmpty())
        <div class="border-t border-gray-50 px-6 pb-6">
            <h4 class="text-sm font-bold text-gray-700 mt-6 mb-4">الجلسات المُدخلة ({{ $group_support_session->children->count() }})</h4>
            <div class="space-y-3">
                @foreach($group_support_session->children as $child)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xs font-bold flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $child->session_name ?: 'بدون اسم' }}</p>
                            <p class="text-[11px] text-gray-400">بواسطة: {{ $child->submitter?->name }} • {{ $child->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full
                            {{ $child->approval_status === 'approved' ? 'text-emerald-700 bg-emerald-50 border border-emerald-100' : 'text-amber-700 bg-amber-50 border border-amber-100' }}">
                            {{ $child->approval_status === 'approved' ? 'معتمد' : 'قيد المراجعة' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ─── TAB: Entry Data ─── --}}
        @else
        <form action="{{ route('project_manager.services.group_support_session.update', $group_support_session) }}" method="POST" class="p-6 space-y-6">
            @csrf @method('PUT')
            <input type="hidden" name="tab" value="entry">

            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-emerald-600 font-bold">إضافة جلسة دعم جماعي للمشروع: {{ $group_support_session->project_name }}</p>
                </div>
            </div>

            @include('entry.services.group_support_session.partials.form_details', ['group_support_session' => new \App\Models\GroupSupportSession()])

            <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-600/20">
                    💾 حفظ الجلسة وإضافة أخرى
                </button>
                <a href="{{ route('project_manager.services.group_support_session.index') }}" class="text-sm text-gray-400 hover:text-gray-600 px-4 font-bold">الانتهاء</a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
