@extends('layouts.app')
{{-- resources/views/manager/projects/show.blade.php --}}

@section('title', 'تفاصيل المشروع — ' . $project->name)
@section('page-title', $project->name)

@section('content')
<div class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── Stats Bar ─── --}}
    @php
        $statusMap  = [
            'active'    => ['جاري العمل', 'text-emerald-600 bg-emerald-50 border-emerald-200'],
            'completed' => ['مكتمل',       'text-indigo-600 bg-indigo-50 border-indigo-200'],
            'draft'     => ['مسودة',        'text-gray-500 bg-gray-50 border-gray-200'],
            'cancelled' => ['ملغي',         'text-rose-600 bg-rose-50 border-rose-200'],
        ];
        $st = $statusMap[$project->status] ?? [$project->status, 'text-gray-500 bg-gray-50 border-gray-100'];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Status --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-bold mb-0.5">الحالة</p>
                <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full border {{ $st[1] }}">{{ $st[0] }}</span>
            </div>
        </div>

        {{-- Submissions count --}}
        @php $totalChildren = ($project->submissions ?? collect())->count(); @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-bold mb-0.5">إجمالي الإدخالات</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalChildren }}</p>
            </div>
        </div>

        {{-- Approved --}}
        @php $approvedCount = ($project->submissions ?? collect())->where('approval_status','approved')->count(); @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-bold mb-0.5">مقبول</p>
                <p class="text-xl font-bold text-emerald-600">{{ $approvedCount }}</p>
            </div>
        </div>

        {{-- Pending --}}
        @php $pendingCount = ($project->submissions ?? collect())->where('approval_status','pending')->count(); @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 font-bold mb-0.5">قيد المراجعة</p>
                <p class="text-xl font-bold text-amber-600">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>

    {{-- ─── Main Grid ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Submissions list --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Project Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">معلومات المشروع</h3>
                        @if(isset($project->service_type))
                        @php
                            $svcLabels = [
                                'training'=>'تدريب وبناء قدرات','economic_empowerment'=>'تمكين اقتصادي',
                                'awareness_workshop'=>'ورشة توعوية','legal_consultation'=>'استشارة قانونية',
                                'psychological_consultation'=>'استشارة نفسية','judicial_representation'=>'تمثيل قضائي',
                                'legal_representation'=>'تمثيل قانوني','mediation'=>'وساطة',
                                'individual_support_session'=>'جلسة دعم فردية','group_support_session'=>'جلسة دعم جماعية',
                            ];
                        @endphp
                        <span class="inline-block mt-1 text-[10px] font-bold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600">
                            {{ $svcLabels[$project->service_type] ?? $project->service_type }}
                        </span>
                        @endif
                    </div>
                    <a href="{{ $project->edit_route ?? route('project_manager.projects.edit', $project) }}"
                       class="text-xs text-indigo-600 hover:underline font-bold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        تعديل
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">الممول</p>
                        <p class="text-sm font-bold text-gray-800">{{ $project->funder ?? $project->description ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">الفترة الزمنية</p>
                        <p class="text-sm font-bold text-gray-800">
                            {{ $project->start_date ? (is_string($project->start_date) ? $project->start_date : $project->start_date->format('Y/m/d')) : '—' }}
                            —
                            {{ $project->end_date ? (is_string($project->end_date) ? $project->end_date : $project->end_date->format('Y/m/d')) : '—' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Submissions / Entries --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-800">سجل الإدخالات التفصيلية</h3>
                    <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-lg">
                        {{ $totalChildren }} إدخال
                    </span>
                </div>

                @if(($project->submissions ?? collect())->isNotEmpty())
                    <div class="divide-y divide-gray-50">
                        @foreach($project->submissions as $sub)
                        <div class="p-5 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                {{-- Entry info --}}
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0
                                        {{ $sub->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-600' : ($sub->approval_status === 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600') }}">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        {{-- Dynamic fields per service type --}}
                                        @if(isset($sub->full_name))
                                            <p class="text-sm font-bold text-gray-800">{{ $sub->full_name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $sub->id_number ? 'هوية: '.$sub->id_number : '' }} {{ $sub->phone ? '— جوال: '.$sub->phone : '' }}</p>
                                        @elseif(isset($sub->meeting_name))
                                            <p class="text-sm font-bold text-gray-800">{{ $sub->meeting_name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $sub->meeting_location ? 'المكان: '.$sub->meeting_location : '' }}</p>
                                        @elseif(isset($sub->case_code))
                                            <p class="text-sm font-bold text-gray-800">{{ $sub->case_code ? 'كود: '.$sub->case_code : 'جلسة فردية' }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $sub->specialist ? 'الأخصائية: '.$sub->specialist : '' }}</p>
                                        @elseif(isset($sub->project_name))
                                            <p class="text-sm font-bold text-gray-800">{{ $sub->project_name }}</p>
                                        @else
                                            <p class="text-sm font-bold text-gray-800">إدخال #{{ $loop->iteration }}</p>
                                        @endif

                                        {{-- Meta --}}
                                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                            <span class="text-[10px] text-gray-400">
                                                بواسطة: <strong>{{ $sub->submitter?->name ?? '—' }}</strong>
                                            </span>
                                            <span class="text-[10px] text-gray-300">•</span>
                                            <span class="text-[10px] text-gray-400">
                                                {{ $sub->created_at->format('Y/m/d H:i') }}
                                            </span>
                                            @if($sub->approval_status === 'rejected' && $sub->rejection_reason)
                                                <span class="text-[10px] text-rose-500 font-bold">
                                                    سبب الرفض: {{ Str::limit($sub->rejection_reason, 60) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Badge + Approval Actions --}}
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    @php
                                        $badge = [
                                            'approved' => ['مقبول', 'text-emerald-700 bg-emerald-50 border-emerald-200'],
                                            'pending'  => ['قيد المراجعة', 'text-amber-700 bg-amber-50 border-amber-200'],
                                            'rejected' => ['مرفوض', 'text-rose-700 bg-rose-50 border-rose-200'],
                                        ][$sub->approval_status] ?? [$sub->approval_status, 'text-gray-400 bg-gray-50 border-gray-100'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badge[1] }}">
                                        {{ $badge[0] }}
                                    </span>

                                    @if($sub->approval_status === 'pending')
                                        <div class="flex items-center gap-1">
                                            <form action="{{ route('project_manager.approvals.approve', [$sub->type_key ?? 'training', $sub->id]) }}" method="POST">
                                                @csrf
                                                <button class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-all">
                                                    قبول
                                                </button>
                                            </form>
                                            <button onclick="document.getElementById('reject-modal-{{ $sub->id }}').classList.remove('hidden')"
                                                    class="px-2.5 py-1 text-[10px] font-bold bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-all">
                                                رفض
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Images (for economic projects) --}}
                            @if(isset($sub->images) && $sub->images->isNotEmpty())
                            <div class="mt-4 flex gap-3 overflow-x-auto pb-1">
                                @foreach($sub->images as $img)
                                    <img src="{{ Storage::url($img->path) }}"
                                         alt="صورة المشروع"
                                         class="w-28 h-20 object-cover rounded-xl border border-gray-100 shadow-sm flex-shrink-0">
                                @endforeach
                            </div>
                            @endif

                            {{-- Reject Modal --}}
                            @if($sub->approval_status === 'pending')
                            <div id="reject-modal-{{ $sub->id }}" class="hidden mt-4">
                                <form action="{{ route('project_manager.approvals.reject', [$sub->type_key ?? 'training', $sub->id]) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="text" name="reason" placeholder="سبب الرفض..." required
                                           class="flex-1 bg-rose-50 border border-rose-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-rose-400 transition-all font-medium">
                                    <button class="px-4 py-2 text-xs font-bold bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-all">
                                        تأكيد الرفض
                                    </button>
                                    <button type="button" onclick="document.getElementById('reject-modal-{{ $sub->id }}').classList.add('hidden')"
                                            class="px-3 py-2 text-xs font-bold text-gray-400 hover:text-gray-600">
                                        إلغاء
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm text-gray-400 font-bold">لا توجد إدخالات بعد</p>
                        @if(isset($project->edit_route))
                        <a href="{{ $project->edit_route }}?tab=entry"
                           class="inline-block mt-3 text-xs text-indigo-600 font-bold hover:underline">
                            أضف أول إدخال
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-bold text-gray-700 mb-4">إجراءات سريعة</h4>
                <div class="space-y-2">
                    @if(isset($project->edit_route))
                    <a href="{{ $project->edit_route }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-50 transition-colors group">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-600">تعديل البيانات الأساسية</span>
                    </a>
                    <a href="{{ $project->edit_route }}?tab=entry"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-50 transition-colors group">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-emerald-600">إضافة إدخال جديد</span>
                    </a>
                    @endif
                    <a href="{{ route('project_manager.approvals.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-colors group">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-amber-600">إدارة الموافقات</span>
                    </a>
                    <a href="{{ route('project_manager.projects.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900">العودة للمشاريع</span>
                    </a>
                </div>
            </div>

            {{-- Team Card --}}
            @if(isset($project->dataEntryUsers))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-bold text-gray-700">طاقم العمل</h4>
                    <a href="{{ route('project_manager.projects.edit', $project) }}" class="text-xs text-indigo-600 hover:underline font-bold">تعديل</a>
                </div>
                <div class="space-y-3">
                    @forelse($project->dataEntryUsers as $staff)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm flex-shrink-0">
                            {{ mb_strtoupper(mb_substr($staff->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $staff->name }}</p>
                            <p class="text-[10px] text-gray-400">مدخل بيانات</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 bg-gray-50 rounded-xl p-3 border border-dashed border-gray-200 text-center italic">
                        لم يتم تعيين أي موظف بعد
                    </p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Approval summary --}}
            <div class="bg-indigo-600 from-indigo-600 to-indigo-700 rounded-2xl p-5 text-white shadow-xl shadow-indigo-600/20">
                <h4 class="font-bold text-sm mb-4 opacity-90">ملخص الموافقات</h4>
                <div class="space-y-3">
                    @php
                        $all = $project->submissions ?? collect();
                        $stats = [
                            ['مقبول',           $all->where('approval_status','approved')->count(), 'bg-emerald-400/30'],
                            ['قيد المراجعة',    $all->where('approval_status','pending')->count(),  'bg-amber-400/30'],
                            ['مرفوض',           $all->where('approval_status','rejected')->count(), 'bg-rose-400/30'],
                        ];
                    @endphp
                    @foreach($stats as [$label, $count, $bg])
                    <div class="flex items-center justify-between p-2.5 rounded-xl {{ $bg }}">
                        <span class="text-xs font-bold opacity-90">{{ $label }}</span>
                        <span class="text-sm font-bold">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
