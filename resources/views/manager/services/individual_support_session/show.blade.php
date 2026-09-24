@extends('layouts.app')

@section('title', 'تفاصيل جلسات الدعم الفردية')
@section('page-title', $individual_support_session->project_name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $individual_support_session->project_name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">جلسات دعم فردية</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $individual_support_session->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('project_manager.services.individual_support_session.edit', $individual_support_session) }}" 
               class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all">تعديل البيانات الأساسية</a>
            <a href="{{ route('project_manager.services.individual_support_session.edit', [$individual_support_session, 'tab' => 'entry']) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة حالة/جلسة جديدة
            </a>
        </div>
    </div>

    {{-- ─── STATS GRID ──────────────────────────────────────────────── --}}
    @php
        $childrenAll = $individual_support_session->children;
        $approvedChildren = $childrenAll->where('approval_status','approved');
        $totalSessions = $approvedChildren->sum(fn($c) => $c->details->count());
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">إجمالي الحالات المعتمدة</span>
                <span class="text-3xl font-black text-slate-800">{{ $approvedChildren->count() }}</span>
                <span class="text-[11px] text-indigo-500 font-bold mt-1 italic">حالة مسجلة</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">إجمالي الجلسات المنجزة</span>
                <span class="text-3xl font-black text-emerald-600">{{ $totalSessions }}</span>
                <span class="text-[11px] text-emerald-500 font-bold mt-1 tracking-widest">جلسة دعم</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الاخصائيون المشاركون</span>
                <span class="text-3xl font-black text-purple-600">{{ $approvedChildren->pluck('specialist')->unique()->count() }}</span>
                <span class="text-[11px] text-purple-400 font-bold mt-1 italic">كادر مختص</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">طلبات معلقة</span>
                <span class="text-3xl font-black text-amber-500">{{ $childrenAll->where('approval_status','pending')->count() }}</span>
                <span class="text-[11px] text-amber-400 font-bold mt-1">بانتظار المراجعة</span>
            </div>
        </div>
    </div>

    {{-- ─── PROJECT DETAILS ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-50 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                بيانات المشروع المظلة
            </h3>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الجهة الممولة</p>
                <p class="text-base font-bold text-slate-700">{{ $individual_support_session->funder ?: '—' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الفترة الزمنية</p>
                <p class="text-base font-bold text-slate-700">
                    {{ $individual_support_session->start_date?->format('Y/m/d') }} — {{ $individual_support_session->end_date?->format('Y/m/d') }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $individual_support_session->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                    {{ $individual_support_session->status == 'active' ? 'نشط' : 'مكتمل' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ─── ACTUAL EXECUTION LIST (APPROVED ONLY) ─────────────────── --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between pr-4">
            <h3 class="text-lg font-bold text-slate-800 border-r-4 border-emerald-500 pr-4">سجل الحالات المعتمدة</h3>
            <span class="text-xs font-bold text-gray-400">{{ $approvedChildren->count() }} حالة مقبولة</span>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($approvedChildren as $child)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md" x-data="{ expanded: false }">
                {{-- Card Header --}}
                <div class="p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-5 flex-1">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-lg font-bold text-slate-800 truncate">
                                {{ $child->beneficiary_name ?: 'بدون اسم' }}
                                <span class="text-xs text-gray-400 font-normal mr-2">/ كود: {{ $child->case_code ?: '—' }}</span>
                            </h4>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-xs text-gray-400 font-bold">
                                <span>📍 {{ $child->address ?: '—' }}</span>
                                <span>🆔 {{ $child->id_number ?: '—' }}</span>
                                <span>📞 {{ $child->mobile ?: '—' }}</span>
                                <span class="text-indigo-500">✍️ {{ $child->submitter?->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-left bg-indigo-50 px-5 py-2.5 rounded-2xl border border-indigo-100">
                            <p class="text-[9px] text-indigo-400 font-bold uppercase tracking-widest mb-0.5">عدد الجلسات</p>
                            <p class="text-lg font-black text-indigo-600">{{ $child->details->count() }}</p>
                        </div>
                        <button @click="expanded = !expanded" 
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-50 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100">
                            <span>عرض سجل الجلسات</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Card Content (Expanded) --}}
                <div x-show="expanded" x-collapse>
                    <div class="px-8 pb-8 pt-2 border-t border-gray-50 bg-slate-50/20">
                        {{-- Basic Info Table --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الأخصائي المتابع</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->specialist ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">تاريخ الميلاد</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->birth_date ? $child->birth_date->format('Y/m/d') : '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">المحافظة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->governorate ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الإعاقة</p>
                                <span class="px-2 py-0.5 rounded-md {{ $child->disability_type ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'text-gray-300' }} text-[10px] font-bold">
                                    {{ $child->disability_type ?: 'لا يوجد' }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة الاجتماعية</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->marital_status ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">تاريخ التسجيل</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->created_at->format('Y/m/d') }}</p>
                            </div>
                        </div>

                        {{-- Session Log --}}
                        <div class="mt-8 space-y-4">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4">سجل الجلسات الإرشادية</h5>
                            @foreach($child->details as $detail)
                            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                                    <span class="bg-indigo-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-sm shadow-indigo-100">الجلسة #{{ $detail->session_number }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $detail->session_date?->format('Y/m/d') }} — {{ $detail->session_time }}</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <p class="text-[10px] text-indigo-400 font-bold mb-2 uppercase tracking-widest">التدخل الإرشادي</p>
                                        <div class="text-sm text-slate-600 leading-relaxed">{{ $detail->intervention_file }}</div>
                                    </div>
                                    <div class="p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100">
                                        <p class="text-[10px] text-emerald-500 font-bold mb-2 uppercase tracking-widest">ملخص الجلسة ومخرجاتها</p>
                                        <div class="text-sm text-slate-600 leading-relaxed">{{ $detail->intervention_summary }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Attachments --}}
                        @if($child->attachments->count() > 0)
                        <div class="mt-8">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4">المرفقات والوثائق</h5>
                            <div class="flex flex-wrap gap-3">
                                @foreach($child->attachments as $attachment)
                                <a href="{{ route('private.file', ['path' => $attachment->file_path]) }}" target="_blank"
                                   class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-100 rounded-2xl text-[10px] font-bold text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    {{ $attachment->file_name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-100">
                <p class="text-sm font-bold text-gray-400">لا توجد حالات دعم فردي معتمدة حالياً.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
