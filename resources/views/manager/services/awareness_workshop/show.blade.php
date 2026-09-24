@extends('layouts.app')

@section('title', 'تفاصيل الورشة التوعوية')
@section('page-title', $awareness_workshop->project_name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $awareness_workshop->project_name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">ورشة عمل توعوية</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $awareness_workshop->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('project_manager.services.awareness_workshop.edit', $awareness_workshop) }}" 
               class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all">تعديل البيانات الأساسية</a>
            <a href="{{ route('project_manager.services.awareness_workshop.edit', [$awareness_workshop, 'tab' => 'entry']) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة تنفيذ جديد
            </a>
        </div>
    </div>

    {{-- ─── STATS GRID ──────────────────────────────────────────────── --}}
    @php
        $childrenAll = $awareness_workshop->children;
        $approvedChildren = $childrenAll->where('approval_status','approved');
        
        $totalAttendees = $approvedChildren->sum(fn($c) => $c->attendees->count());
        $targetAttendees = $awareness_workshop->total_beneficiaries ?: 0;
        $progressPercent = $targetAttendees > 0 ? min(100, round(($totalAttendees / $targetAttendees) * 100)) : 0;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">المستهدف الكلي</span>
                <span class="text-3xl font-black text-slate-800">{{ $targetAttendees }}</span>
                <span class="text-[11px] text-indigo-500 font-bold mt-1 italic">مستفيد مخطط له</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الإنجاز الفعلي</span>
                <span class="text-3xl font-black text-emerald-600">{{ $totalAttendees }}</span>
                <div class="flex items-center gap-2 mt-1">
                    <div class="flex-1 bg-gray-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <span class="text-[11px] text-emerald-500 font-black">{{ $progressPercent }}%</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">جلسات معتمدة</span>
                <span class="text-3xl font-black text-purple-600">{{ $approvedChildren->count() }}</span>
                <span class="text-[11px] text-purple-400 font-bold mt-1">تمت الموافقة عليها</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">طلبات معلقة</span>
                <span class="text-3xl font-black text-amber-500">{{ $childrenAll->where('approval_status','pending')->count() }}</span>
                <span class="text-[11px] text-amber-400 font-bold mt-1 italic">بانتظار المراجعة</span>
            </div>
        </div>
    </div>

    {{-- ─── PROJECT DETAILS ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-50 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                البيانات الأساسية للورشة
            </h3>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الجهة الممولة</p>
                <p class="text-base font-bold text-slate-700">{{ $awareness_workshop->funder ?: '—' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الفترة الزمنية</p>
                <p class="text-base font-bold text-slate-700">
                    {{ $awareness_workshop->start_date?->format('Y/m/d') }} — {{ $awareness_workshop->end_date?->format('Y/m/d') }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">حالة المشروع</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $awareness_workshop->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $awareness_workshop->status == 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                    {{ $awareness_workshop->status == 'active' ? 'نشط حالياً' : 'مكتمل/غير نشط' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ─── ACTUAL EXECUTION LIST (APPROVED ONLY) ─────────────────── --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between pr-4">
            <h3 class="text-lg font-bold text-slate-800 border-r-4 border-emerald-500 pr-4">سجل الإدخالات المعتمدة (التنفيذ الفعلي)</h3>
            <span class="text-xs font-bold text-gray-400">{{ $approvedChildren->count() }} إدخال مقبول</span>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($approvedChildren as $child)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md" x-data="{ expanded: false, showAttendees: false }">
                {{-- Card Header --}}
                <div class="p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-5 flex-1">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-black flex-shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-lg font-bold text-slate-800 truncate">{{ $child->meeting_name ?: 'إدخال بدون اسم' }}</h4>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-xs text-gray-400">
                                <span class="flex items-center gap-1.5 font-bold"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> {{ $child->meeting_location ?: '—' }}</span>
                                <span class="flex items-center gap-1.5 font-bold"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $child->created_at->format('Y/m/d') }}</span>
                                <span class="flex items-center gap-1.5 font-bold"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> {{ $child->submitter?->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button @click="expanded = !expanded" 
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-50 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100">
                            <span>تفاصيل التنفيذ</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <button @click="showAttendees = !showAttendees" 
                                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-50 text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition-all border border-indigo-100">
                            <span>قائمة المستفيدين</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Card Content (Expanded) --}}
                <div x-show="expanded" x-collapse>
                    <div class="px-8 pb-8 pt-2 border-t border-gray-50">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mt-6">
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">عدد الحضور</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->attendance_count }} مستفيد</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">مدة اللقاء</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->meeting_duration ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">رقم الجلسة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->session_number ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">ميسر الجلسة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->session_facilitator ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">مسير اللقاء</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->meeting_moderator ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الجهة المستضيفة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->hosting_party ?: '—' }}</p>
                            </div>
                        </div>

                        @if($child->description)
                        <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <h5 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">وصف الورشة / ملاحظات التنفيذ</h5>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $child->description }}</p>
                        </div>
                        @endif

                        {{-- Attachments Gallery --}}
                        <div class="mt-8">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                المرفقات والصور ({{ $child->attachments->count() }})
                            </h5>
                            @if($child->attachments->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach($child->attachments as $attachment)
                                    @php
                                        $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <a href="{{ route('private.file', ['path' => $attachment->file_path]) }}" target="_blank"
                                       class="group relative aspect-square bg-slate-100 rounded-2xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-all shadow-sm">
                                        @if($isImage)
                                            <img src="{{ route('private.file', ['path' => $attachment->file_path]) }}" alt="{{ $attachment->file_name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <span class="text-[10px] font-black text-slate-400 uppercase">{{ $ext }}</span>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @else
                            <p class="text-[11px] text-gray-400 italic">لا توجد مرفقات مرفوعة لهذا الإدخال.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Attendees Table (Toggled) --}}
                <div x-show="showAttendees" x-collapse class="bg-slate-50 border-t border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-4 pr-2">
                            <h5 class="text-sm font-bold text-slate-700">قائمة المستفيدين الحاضرين ({{ $child->attendees->count() }})</h5>
                            <button @click="showAttendees = false" class="text-xs text-gray-400 hover:text-rose-500 font-bold">إغلاق القائمة</button>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                            <table class="w-full text-[11px] text-right">
                                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">الاسم الكامل</th>
                                        <th class="px-6 py-4">رقم الهوية</th>
                                        <th class="px-6 py-4">الجوال</th>
                                        <th class="px-6 py-4">التخصص</th>
                                        <th class="px-6 py-4 text-center">المحافظة</th>
                                        <th class="px-6 py-4 text-center">الإعاقة</th>
                                        <th class="px-6 py-4 text-center">تاريخ الميلاد</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($child->attendees as $att)
                                    <tr class="hover:bg-indigo-50/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-black text-slate-800">{{ $att->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium">{{ $att->name_en }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 font-bold">{{ $att->id_number ?: '—' }}</td>
                                        <td class="px-6 py-4 text-indigo-600 font-bold">{{ $att->phone ?: '—' }}</td>
                                        <td class="px-6 py-4 text-slate-500">{{ $att->specialty ?: '—' }}</td>
                                        <td class="px-6 py-4 text-center text-slate-500">{{ $att->governorate ?: '—' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 rounded-md {{ $att->disability_type ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'text-gray-300' }} text-[10px] font-bold">
                                                {{ $att->disability_type ?: 'لا يوجد' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-500">{{ $att->birth_date ? $att->birth_date->format('Y/m/d') : '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 font-bold italic">لا توجد بيانات مستفيدين مسجلة</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-400 mb-1">لا توجد سجلات معتمدة لهذا المشروع</h4>
                <p class="text-sm text-gray-300">بانتظار قيام مدخلي البيانات بإرسال التقارير واعتمادها من قبلكم</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
