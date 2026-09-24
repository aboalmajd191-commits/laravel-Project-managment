@extends('layouts.app')

@section('title', 'لوحة التحكم - مدير المشروع')
@section('page-title', 'مرحباً، ' . auth()->user()->name)

@section('content')
<div class="space-y-8" dir="rtl">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold mb-1">مشاريع جارية</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_projects'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold mb-1 font-bold">مشاريع مكتملة</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completed_projects'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 font-bold font-bold">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold mb-1">إجمالي المستفيدين</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_beneficiaries']) }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 relative overflow-hidden font-bold font-bold font-bold">
            @if($stats['pending_approvals'] > 0)
                <div class="absolute top-0 right-0 w-16 h-16 -mr-8 -mt-8 bg-amber-500/10 rounded-full"></div>
            @endif
            <div class="w-12 h-12 {{ $stats['pending_approvals'] > 0 ? 'bg-amber-50 text-amber-600' : 'bg-gray-50 text-gray-400' }} rounded-xl flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold mb-1">طلبات بانتظارك</p>
                <div class="flex items-center gap-2 font-bold font-bold">
                    <h3 class="text-2xl font-bold text-gray-800 font-bold">{{ $stats['pending_approvals'] }}</h3>
                    @if($stats['pending_approvals'] > 0)
                        <a href="{{ route('project_manager.approvals.index') }}" class="text-[10px] bg-amber-600 text-white px-1.5 py-0.5 rounded-md hover:bg-amber-700 transition-colors">عرض الكل</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- All Projects Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right font-medium">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between font-bold bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800 font-bold">كافة مشروعاتي</h3>
            <span class="text-xs text-gray-500 font-bold font-bold font-bold">إجمالي: {{ $allProjects->count() }}</span>
        </div>
        <div class="overflow-x-auto font-medium">
            <table class="w-full">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-[13px] font-bold text-gray-500 font-bold font-bold">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">الحالة الحالية</th>
                        <th class="px-6 py-4 text-left">تغيير الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($allProjects as $project)
                        <tr class="hover:bg-indigo-50/10 transition-colors font-medium">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $project->name }}</p>
                                <p class="text-[11px] text-gray-400 mt-1 font-bold">{{ $project->service_type ?? 'عام' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold">
                                {{ $project->start_date?->format('Y/m/d') }} - {{ $project->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColor = match($project->status) {
                                        'active' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        'completed' => 'text-blue-600 bg-blue-50 border-blue-100',
                                        'draft' => 'text-gray-500 bg-gray-50 border-gray-100',
                                        default => ''
                                    };
                                    $statusLabel = match($project->status) { 'active' => 'نشط', 'completed' => 'مكتمل', 'draft' => 'مسودة', default => $project->status };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusColor }} font-bold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <form action="{{ route('project_manager.projects.update_status', $project->id) }}" method="POST" class="inline-flex gap-1 font-bold">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $project->model_type }}">
                                        <button name="status" value="active" class="px-2 py-1 text-[10px] rounded bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-all font-bold {{ $project->status == 'active' ? 'ring-2 ring-emerald-500 ring-offset-1' : '' }}">نشط</button>
                                        <button name="status" value="completed" class="px-2 py-1 text-[10px] rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition-all font-bold {{ $project->status == 'completed' ? 'ring-2 ring-blue-500 ring-offset-1' : '' }}">مكتمل</button>
                                        <button name="status" value="draft" class="px-2 py-1 text-[10px] rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all font-bold {{ $project->status == 'draft' ? 'ring-2 ring-gray-500 ring-offset-1' : '' }}">مسودة</button>
                                    </form>
                                 </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold font-medium font-bold font-bold">لا يوجد مشاريع مسجلة حالياً</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($allProjects->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-center font-bold">
                {{ $allProjects->links() }}
            </div>
        @endif
    </div>

    {{-- Filtered Lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 font-medium">
        {{-- Active Projects --}}
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden text-right">
            <div class="p-5 border-b border-emerald-50 bg-emerald-50/30 flex items-center justify-between font-bold">
                <h4 class="text-sm font-bold text-emerald-800">المشاريع النشطة حالياً</h4>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="p-2 divide-y divide-emerald-50 font-medium">
                @forelse($activeProjects as $ap)
                    <div class="p-4 hover:bg-emerald-50/20 transition-all rounded-xl mt-1 first:mt-0 font-medium">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-gray-800 font-bold">{{ $ap->name }}</span>
                            <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-bold">{{ $ap->start_date?->format('Y/m/d') }}</span>
                        </div>
                        <p class="text-[11px] text-gray-400">{{ Str::limit($ap->description ?? '', 60) }}</p>
                    </div>
                @empty
                    <p class="py-12 text-center text-gray-300 text-xs font-bold font-bold font-bold font-bold">لا توجد مشاريع نشطة</p>
                @endforelse
            </div>
        </div>

        {{-- Completed Projects --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden text-right font-medium">
            <div class="p-5 border-b border-blue-50 bg-blue-50/30 flex items-center justify-between font-bold font-bold">
                <h4 class="text-sm font-bold text-blue-800 font-bold">المشاريع المكتملة</h4>
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="p-2 divide-y divide-blue-50 font-medium">
                @forelse($completedProjects as $cp)
                    <div class="p-4 hover:bg-blue-50/20 transition-all rounded-xl mt-1 first:mt-0 font-medium font-medium font-medium font-medium font-medium">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-gray-800 font-bold">{{ $cp->name }}</span>
                            <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded font-bold">{{ $cp->end_date?->format('Y/m/d') }}</span>
                        </div>
                        <p class="text-[11px] text-gray-400 font-bold">{{ Str::limit($cp->description ?? '', 60) }}</p>
                    </div>
                @empty
                    <p class="py-12 text-center text-gray-300 text-xs font-bold">لا توجد مشاريع مكتملة</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Action Center --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-6 font-medium font-bold font-bold">
        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="text-base font-bold text-gray-800 mb-6 font-bold">إجراءات سريعة</h4>
            <div class="grid grid-cols-2 gap-4 font-bold font-bold font-bold font-bold">
                <a href="{{ route('project_manager.approvals.index') }}" class="flex flex-col items-center gap-3 p-6 rounded-2xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-all border border-emerald-100 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold">إدارة الموافقات</span>
                </a>
                <a href="{{ route('project_manager.projects.index') }}" class="flex flex-col items-center gap-3 p-6 rounded-2xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-all border border-indigo-100 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-sm font-bold">إضافة مشروع</span>
                </a>
            </div>
        </div>

        <div class="bg-indigo-600 from-slate-800 to-slate-900 p-8 rounded-2xl text-white shadow-xl relative overflow-hidden flex flex-col justify-center">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 -ml-32 -mt-32 rounded-full blur-3xl"></div>
            <h4 class="text-lg font-bold mb-2">تلميحة العمل</h4>
            <p class="text-slate-400 text-sm leading-relaxed font-bold">
                يمكنك تحديث حالة مشروعك بسرعة من خلال قائمة "كافة مشروعاتي" أعلاه. 
                المشاريع التي يتم تحويلها إلى "مكتملة" تُحفظ في الأرشيف وتظهر في القائمة الزرقاء أدناه.
            </p>
        </div>
    </div>

</div>
@endsection
