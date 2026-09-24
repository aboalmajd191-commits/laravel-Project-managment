@extends('layouts.app')

@section('title', 'تفاصيل الخدمة - عرض إداري')
@section('page-title', $record->project_name ?? $record->name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $record->project_name ?? $record->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">{{ $label }}</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $record->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-600">
                الحالة: 
                <span class="{{ $record->status == 'active' ? 'text-emerald-600' : ($record->status == 'completed' ? 'text-blue-600' : 'text-gray-400') }}">
                    {{ ['active'=>'نشط', 'completed'=>'مكتمل', 'draft'=>'مسودة'][$record->status] ?? $record->status }}
                </span>
            </span>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-xs font-bold hover:bg-black transition-all shadow-lg shadow-slate-200">عودة للوحة التحكم</a>
        </div>
    </div>

    {{-- ─── STATS & INFO GRID ──────────────────────────────────────── --}}
    @php
        $stats = [
            'total' => $children->total(),
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الجهة الممولة</p>
            <p class="text-base font-bold text-slate-700">{{ $record->funder ?? $record->funding_agency ?? '—' }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الفترة الزمنية</p>
            <p class="text-base font-bold text-slate-700">{{ optional($record->start_date)->format('Y/m/d') }} — {{ optional($record->end_date)->format('Y/m/d') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">نوع القطاع</p>
            <span class="inline-flex px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold border border-indigo-100 uppercase">
                {{ ['protection'=>'الحماية', 'education'=>'التعليم', 'health'=>'الصحة'][$record->sector_type] ?? $record->sector_type ?? '—' }}
            </span>
        </div>
        <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl shadow-indigo-100 text-white">
            <p class="text-[10px] opacity-70 font-bold uppercase tracking-widest mb-1">إجمالي التنفيذات المعتمدة</p>
            <p class="text-2xl font-black">{{ $children->count() }}</p>
        </div>
    </div>

    @if($record->project_description)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-widest mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            وصف المشروع وتفاصيل إدارية
        </h4>
        <p class="text-sm text-slate-600 leading-relaxed">{{ $record->project_description }}</p>
    </div>
    @endif

    {{-- ─── ENTRIES LIST (FULL WIDTH) ─────────────────────────────── --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between pr-4">
            <h3 class="text-lg font-bold text-slate-800 border-r-4 border-indigo-500 pr-4">سجل الإدخالات المعتمدة</h3>
            <span class="text-xs font-bold text-gray-400">{{ $children->total() }} سجل تنفيذ</span>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($children as $child)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md" x-data="{ expanded: false }">
                <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-base font-black">
                            {{ $loop->iteration }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-lg font-bold text-slate-800 truncate">
                                @if($type === 'training' || $type === 'awareness_workshop')
                                    {{ ($type === 'training' && $child->activity_name) ? $child->activity_name : ($child->name ?: ($type === 'training' ? 'دورة تدريبية' : 'ورشة عمل')) }}
                                @elseif($type === 'economic_empowerment')
                                    {{ $child->owner_name }}
                                @elseif($type === 'individual_support_session')
                                    {{ $child->beneficiary_name ?: 'حالة: ' . $child->case_code }}
                                @else
                                    {{ $child->full_name ?? $child->beneficiary_name ?? $child->project_name ?? 'سجل تنفيذ' }}
                                @endif
                            </h4>
                            @if($type === 'training' && $child->activity_name)
                                <p class="text-[10px] text-indigo-500 font-bold mt-1">الخدمة: {{ $child->name }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-xs text-gray-400 font-bold">
                                <span>📍 {{ $child->location ?? $child->meeting_location ?? $child->governorate ?? $child->address ?? '—' }}</span>
                                <span>📅 {{ optional($child->start_date ?? $child->project_date ?? $child->created_at)->format('Y/m/d') }}</span>
                                <span class="text-indigo-500">✍️ {{ $child->submitter?->name }}</span>
                            </div>
                        </div>
                    </div>
                    <button @click="expanded = !expanded" 
                            class="px-6 py-2.5 rounded-2xl bg-slate-50 text-slate-600 text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100 flex items-center gap-2">
                        عرض التفاصيل
                        <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <div x-show="expanded" x-collapse>
                    <div class="px-8 pb-8 pt-2 border-t border-gray-50 bg-slate-50/30">
                        {{-- Data Grid Specific to Service --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
                            @if($type === 'training' || $type === 'awareness_workshop' || $type === 'group_support_session')
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">عدد المستفيدين</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->attendees->count() }}</p>
                                </div>
                                @if($type === 'training')
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">اسم النشاط</p>
                                    <p class="text-sm font-black text-indigo-600">{{ $child->activity_name ?: '—' }}</p>
                                </div>
                                @endif
                            @elseif($type === 'economic_empowerment')
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">قيمة المنحة</p>
                                    <p class="text-sm font-black text-emerald-600">${{ number_format($child->grant_value) }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">رقم الهوية</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->id_number ?: '—' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">عدد الأفراد</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->individuals_count }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">المحافظة</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->governorate ?: '—' }}</p>
                                </div>
                            @elseif($type === 'individual_support_session')
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">رقم الهوية / الكود</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->id_number ?: ($child->case_code ?: '—') }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">الأخصائي</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->specialist }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">رقم الجوال</p>
                                    <p class="text-sm font-black text-indigo-600">{{ $child->mobile ?: '—' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">عدد الجلسات</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->details->count() }}</p>
                                </div>
                            @else
                                {{-- Generic Fields for other services --}}
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">رقم الهوية</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->id_number ?: '—' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">رقم الجوال</p>
                                    <p class="text-sm font-black text-indigo-600">{{ $child->mobile ?: ($child->phone ?: '—') }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">تاريخ الميلاد</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->birth_date ? $child->birth_date->format('Y/m/d') : '—' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">الحالة الاجتماعية</p>
                                    <p class="text-sm font-black text-slate-700">{{ $child->marital_status ?: '—' }}</p>
                                </div>
                            @endif
                       
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">تاريخ الاعتماد</p>
                                <p class="text-sm font-black text-slate-700">{{ optional($child->approved_at)->format('Y/m/d') ?: 'تلقائي' }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($child->description || $child->case_description || $child->intervention_summary)
                        <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                            <h5 class="text-[10px] text-slate-400 font-bold uppercase mb-2">موجز التنفيذ / الوصف</h5>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $child->description ?? $child->case_description ?? $child->intervention_summary }}</p>
                        </div>
                        @endif

                        {{-- Attachments --}}
                        @if(method_exists($child, 'attachments') && $child->attachments->count() > 0)
                        <div class="mt-8">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4 flex items-center gap-2">المرفقات والملفات ({{ $child->attachments->count() }})</h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach($child->attachments as $attachment)
                                    <a href="{{ route('private.file', ['path' => $attachment->file_path]) }}" target="_blank"
                                       class="p-4 bg-white rounded-2xl border border-gray-100 hover:border-indigo-500 transition-all shadow-sm flex flex-col items-center gap-2 group">
                                        <svg class="w-8 h-8 text-slate-200 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span class="text-[9px] font-black text-slate-400 text-center truncate w-full">{{ $attachment->file_name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Attendees/Details Toggle --}}
                        @if(($type === 'training' || $type === 'awareness_workshop' || $type === 'group_support_session') && $child->attendees->count() > 0)
                            <div class="mt-8">
                                <h5 class="text-[11px] font-black text-slate-800 mb-4">قائمة الأسماء المسجلة</h5>
                                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                                <table class="w-full text-[10px] text-right">
                                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-gray-100">
                                <tr>
                                                <th class="px-4 py-3">الاسم</th>
                                                <th class="px-4 py-3">رقم الهوية</th>
                                                <th class="px-4 py-3">الجوال</th>
                                                <th class="px-4 py-3">التخصص</th>
                                                <th class="px-4 py-3">المحافظة</th>
                                                <th class="px-4 py-3 text-center">الإعاقة</th>
                                                <th class="px-4 py-3 text-center">تاريخ الميلاد</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($child->attendees as $att)
                                            <tr>
                                                <td class="px-4 py-3 font-bold text-slate-700">
                                                    {{ $att->name ?? $att->beneficiary_name }}
                                                    <div class="text-[8px] text-gray-400 font-normal">{{ $att->name_en }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-slate-500 font-bold">{{ $att->id_number ?: '—' }}</td>
                                                <td class="px-4 py-3 text-indigo-600 font-bold">{{ $att->phone ?? ($att->mobile ?? '—') }}</td>
                                                <td class="px-4 py-3 text-slate-500">{{ $att->specialty ?: '—' }}</td>
                                                <td class="px-4 py-3 text-slate-500">{{ $att->governorate ?: '—' }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="px-2 py-0.5 rounded-md {{ $att->disability_type ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'text-gray-300' }} text-[9px] font-bold">
                                                        {{ $att->disability_type ?: 'لا يوجد' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center text-slate-400">{{ $att->birth_date ? $att->birth_date->format('Y/m/d') : '—' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-100">
                <p class="text-sm font-bold text-gray-400">لا توجد سجلات تنفيذ معتمدة حالياً.</p>
            </div>
            @endforelse
        </div>
        @if($children->hasPages())
            <div class="mt-6 px-8 py-4 bg-white rounded-3xl shadow-sm border border-gray-100">
                {{ $children->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
