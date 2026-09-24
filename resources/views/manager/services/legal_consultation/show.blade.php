@extends('layouts.app')

@section('title', 'تفاصيل الاستشارات القانونية')
@section('page-title', $legal_consultation->project_name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $legal_consultation->project_name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">استشارات قانونية</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $legal_consultation->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('project_manager.services.legal_consultation.edit', $legal_consultation) }}" 
               class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all">تعديل البيانات الأساسية</a>
            <a href="{{ route('project_manager.services.legal_consultation.edit', [$legal_consultation, 'tab' => 'entry']) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة استشارة جديدة
            </a>
        </div>
    </div>

    {{-- ─── STATS GRID ──────────────────────────────────────────────── --}}
    @php
        $childrenAll = $legal_consultation->children;
        $approvedChildren = $childrenAll->where('approval_status','approved');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">إجمالي الاستشارات المعتمدة</span>
                <span class="text-3xl font-black text-slate-800">{{ $approvedChildren->count() }}</span>
                <span class="text-[11px] text-indigo-500 font-bold mt-1 italic">استشارة قانونية</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الذكور</span>
                <span class="text-3xl font-black text-emerald-600">{{ $approvedChildren->where('gender','male')->count() }}</span>
                <span class="text-[11px] text-emerald-500 font-bold mt-1">مستفيد ذكر</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الإناث</span>
                <span class="text-3xl font-black text-rose-600">{{ $approvedChildren->where('gender','female')->count() }}</span>
                <span class="text-[11px] text-rose-400 font-bold mt-1">مستفيدة أنثى</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">طلبات معلقة</span>
                <span class="text-3xl font-black text-amber-500">{{ $childrenAll->where('approval_status','pending')->count() }}</span>
                <span class="text-[11px] text-amber-400 font-bold mt-1 tracking-widest">تحتاج مراجعة</span>
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
                <p class="text-base font-bold text-slate-700">{{ $legal_consultation->funder ?: '—' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الفترة الزمنية</p>
                <p class="text-base font-bold text-slate-700">
                    {{ $legal_consultation->start_date?->format('Y/m/d') }} — {{ $legal_consultation->end_date?->format('Y/m/d') }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $legal_consultation->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                    {{ $legal_consultation->status == 'active' ? 'نشط' : 'مكتمل' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ─── ACTUAL EXECUTION LIST (APPROVED ONLY) ─────────────────── --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between pr-4">
            <h3 class="text-lg font-bold text-slate-800 border-r-4 border-emerald-500 pr-4">سجل الاستشارات المعتمدة</h3>
            <span class="text-xs font-bold text-gray-400">{{ $approvedChildren->count() }} استشارة مقبولة</span>
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
                                {{ $child->full_name ?: 'بدون اسم' }}
                                <span class="text-xs text-gray-400 font-normal mr-2">/ هوية: {{ $child->id_number ?: '—' }}</span>
                            </h4>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-xs text-gray-400 font-bold">
                                <span>📍 {{ $child->current_address ?: '—' }}</span>
                                <span>📞 {{ $child->phone ?: '—' }}</span>
                                <span>🎂 {{ $child->birth_date ? $child->birth_date->format('Y/m/d') : '—' }}</span>
                                <span class="text-indigo-500">✍️ {{ $child->submitter?->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-left bg-slate-50 px-5 py-2.5 rounded-2xl border border-slate-100">
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">الجنس</p>
                            <p class="text-sm font-black text-slate-700">{{ $child->gender === 'male' ? 'ذكر' : 'أنثى' }}</p>
                        </div>
                        <button @click="expanded = !expanded" 
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-50 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100">
                            <span>عرض التفاصيل</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Card Content (Expanded) --}}
                <div x-show="expanded" x-collapse>
                    <div class="px-8 pb-8 pt-2 border-t border-gray-50 bg-slate-50/20">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة الاجتماعية</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->marital_status ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">تاريخ الاستشارة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->created_at->format('Y/m/d') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                                <h5 class="text-[10px] text-indigo-400 font-bold uppercase mb-2">وصف المشكلة القانونية</h5>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $child->problem_description }}</p>
                            </div>
                            <div class="p-6 bg-emerald-50/30 rounded-3xl border border-emerald-100 shadow-sm">
                                <h5 class="text-[10px] text-emerald-500 font-bold uppercase mb-2">الإجراء القانوني المتخذ</h5>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $child->legal_intervention }}</p>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        @if($child->attachments->count() > 0)
                        <div class="mt-8">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4">المرفقات والوثائق القانونية</h5>
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
                <p class="text-sm font-bold text-gray-400">لا توجد استشارات قانونية معتمدة حالياً.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
