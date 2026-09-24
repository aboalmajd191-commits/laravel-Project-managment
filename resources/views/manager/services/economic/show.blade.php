@extends('layouts.app')

@section('title', 'تفاصيل مشروع التمكين')
@section('page-title', $economic->project_name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $economic->project_name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">التمكين الاقتصادي</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $economic->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('project_manager.services.economic_empowerment.edit', $economic) }}" 
               class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all">تعديل البيانات الأساسية</a>
            <a href="{{ route('project_manager.services.economic_empowerment.edit', [$economic, 'tab' => 'entry']) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة مستفيد جديد
            </a>
        </div>
    </div>

    {{-- ─── STATS GRID ──────────────────────────────────────────────── --}}
    @php
        $childrenAll = $economic->children;
        $approvedChildren = $childrenAll->where('approval_status','approved');
        
        $totalGrant = $approvedChildren->sum('grant_value');
        $targetGrant = $economic->total_grant_value ?: 0;
        $progressPercent = $targetGrant > 0 ? min(100, round(($totalGrant / $targetGrant) * 100)) : 0;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">إجمالي المنح المخططة</span>
                <span class="text-3xl font-black text-slate-800">${{ number_format($targetGrant) }}</span>
                <span class="text-[11px] text-indigo-500 font-bold mt-1 italic">ميزانية المشروع</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">المنح الموزعة (المعتمدة)</span>
                <span class="text-3xl font-black text-emerald-600">${{ number_format($totalGrant) }}</span>
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
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">عدد المستفيدين</span>
                <span class="text-3xl font-black text-purple-600">{{ $approvedChildren->count() }}</span>
                <span class="text-[11px] text-purple-400 font-bold mt-1">أسرة استفادت</span>
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
                البيانات الأساسية لمشروع التمكين
            </h3>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الجهة الممولة</p>
                <p class="text-base font-bold text-slate-700">{{ $economic->funder ?: '—' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">منسق المشروع</p>
                <p class="text-base font-bold text-slate-700">{{ $economic->coordinator_name ?: '—' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الفترة الزمنية</p>
                <p class="text-base font-bold text-slate-700">
                    {{ $economic->start_date?->format('Y/m/d') }} — {{ $economic->end_date?->format('Y/m/d') }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $economic->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                    {{ $economic->status == 'active' ? 'نشط' : 'مكتمل' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ─── ACTUAL EXECUTION LIST (APPROVED ONLY) ─────────────────── --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between pr-4">
            <h3 class="text-lg font-bold text-slate-800 border-r-4 border-emerald-500 pr-4">قائمة المستفيدين المعتمدين (المنح الموزعة)</h3>
            <span class="text-xs font-bold text-gray-400">{{ $approvedChildren->count() }} مستفيد مقبول</span>
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
                            <h4 class="text-lg font-bold text-slate-800 truncate">{{ $child->owner_name ?: 'إدخال بدون اسم' }}</h4>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2 text-xs text-gray-400 font-bold">
                                <span>📍 {{ $child->governorate ?: '—' }}</span>
                                <span>🆔 {{ $child->id_number ?: '—' }}</span>
                                <span>📞 {{ $child->phone ?: '—' }}</span>
                                <span class="text-indigo-500">✍️ {{ $child->submitter?->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-left bg-emerald-50 px-5 py-2.5 rounded-2xl border border-emerald-100">
                            <p class="text-[9px] text-emerald-400 font-bold uppercase tracking-widest mb-0.5">قيمة المنحة</p>
                            <p class="text-lg font-black text-emerald-600">${{ number_format($child->grant_value) }}</p>
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
                    <div class="px-8 pb-8 pt-2 border-t border-gray-50">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6">
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">عدد أفراد الأسرة</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->individuals_count ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">الحالة الاجتماعية</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->marital_status ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">المستوى التعليمي</p>
                                <p class="text-sm font-black text-slate-700">{{ $child->education_level ?: '—' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">تاريخ المنحة</p>
                                <p class="text-sm font-black text-slate-700">{{ optional($child->grant_date)->format('Y/m/d') ?: '—' }}</p>
                            </div>
                        </div>

                        {{-- Attachments Gallery --}}
                        <div class="mt-8">
                            <h5 class="text-[11px] font-black text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                المرفقات والصور ({{ $child->attachments->count() + $child->images->count() }})
                            </h5>
                            @if($child->attachments->count() > 0 || $child->images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                                {{-- New Attachments System --}}
                                @foreach($child->attachments as $attachment)
                                    @php
                                        $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <a href="{{ route('private.file', ['path' => $attachment->file_path]) }}" target="_blank"
                                       class="group relative aspect-square bg-slate-100 rounded-2xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-all shadow-sm">
                                        @if($isImage)
                                            <img src="{{ route('private.file', ['path' => $attachment->file_path]) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <span class="text-[10px] font-black text-slate-400 uppercase">{{ $ext }}</span>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                                {{-- Legacy Images System --}}
                                @foreach($child->images as $img)
                                    <a href="{{ route('private.file', ['path' => $img->path]) }}" target="_blank"
                                       class="group relative aspect-square bg-slate-100 rounded-2xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-all shadow-sm">
                                        <img src="{{ route('private.file', ['path' => $img->path]) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </a>
                                @endforeach
                            </div>
                            @else
                            <p class="text-[11px] text-gray-400 italic">لا توجد مرفقات مرفوعة لهذا المستفيد.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-100">
                <p class="text-sm font-bold text-gray-400">لا توجد بيانات مستفيدين معتمدة حالياً.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
