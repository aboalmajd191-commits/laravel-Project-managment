@extends('layouts.app')
{{-- resources/views/manager/projects/index.blade.php --}}

@section('title', 'إدارة المشاريع')
@section('page-title', 'مشاريعي')

@section('content')
<div class="space-y-6">

    {{-- Filters & Header --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('project_manager.projects.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="service_type" class="pr-8 pl-4">
                <option value="">كل الخدمات</option>
                <option value="training" {{ request('service_type') === 'training' ? 'selected' : '' }}>تدريب</option>
                <option value="awareness_workshop" {{ request('service_type') === 'awareness_workshop' ? 'selected' : '' }}>ورشة عمل</option>
                <option value="economic_empowerment" {{ request('service_type') === 'economic_empowerment' ? 'selected' : '' }}>تمكين اقتصادي</option>
                <option value="legal_consultation" {{ request('service_type') === 'legal_consultation' ? 'selected' : '' }}>استشارة قانونية</option>
                <option value="psychological_consultation" {{ request('service_type') === 'psychological_consultation' ? 'selected' : '' }}>استشارة نفسية</option>
                <option value="judicial_representation" {{ request('service_type') === 'judicial_representation' ? 'selected' : '' }}>تمثيل قضائي</option>
                <option value="legal_representation" {{ request('service_type') === 'legal_representation' ? 'selected' : '' }}>تمثيل قانوني</option>
                <option value="mediation" {{ request('service_type') === 'mediation' ? 'selected' : '' }}>وساطة</option>
                <option value="individual_support_session" {{ request('service_type') === 'individual_support_session' ? 'selected' : '' }}>جلسة فردية</option>
                <option value="group_support_session" {{ request('service_type') === 'group_support_session' ? 'selected' : '' }}>جلسة جماعية</option>
            </select>

            <select name="status" class="text-xs font-bold rounded-xl border-gray-100 focus:ring-indigo-500 bg-gray-50 pr-8 pl-4 py-2">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>جاري</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
            </select>

            <button type="submit" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>

        <!-- <a href="{{ route('project_manager.projects.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إنشاء مشروع جديد
        </a> -->
    </div>

    {{-- Projects Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">اسم المشروع</th>
                        <th class="px-6 py-4 text-center">نوع الخدمة</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-center">التاريخ</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <a href="{{ $project->show_route }}" class="block">
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $project->name }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate max-w-xs">{{ $project->description ?: 'لا يوجد وصف' }}</p>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $serviceLabels = [
                                        'training' => ['تدريب', 'text-indigo-600 bg-indigo-50'],
                                        'awareness_workshop' => ['ورشة عمل', 'text-purple-600 bg-purple-50'],
                                        'economic_empowerment' => ['تمكين اقتصادي', 'text-emerald-600 bg-emerald-50'],
                                        'cash_assistance' => ['مساعدة نقدية', 'text-amber-600 bg-amber-50'],
                                        'in_kind_assistance' => ['مساعدة عينية', 'text-rose-600 bg-rose-50'],
                                        'community_initiatives' => ['مبادرة مجتمعية', 'text-sky-600 bg-sky-50'],
                                        'support_sponsorship' => ['دعم ورعاية', 'text-teal-600 bg-teal-50'],
                                        'legal_consultation' => ['استشارة قانونية', 'text-blue-600 bg-blue-50'],
                                        'psychological_consultation' => ['استشارة نفسية', 'text-pink-600 bg-pink-50'],
                                        'judicial_representation' => ['تمثيل قضائي', 'text-slate-600 bg-slate-50'],
                                        'legal_representation' => ['تمثيل قانوني', 'text-gray-600 bg-gray-50'],
                                        'mediation' => ['وساطة', 'text-cyan-600 bg-cyan-50'],
                                        'individual_support_session' => ['جلسة فردية', 'text-orange-600 bg-orange-50'],
                                        'group_support_session' => ['جلسة جماعية', 'text-yellow-600 bg-yellow-50'],
                                    ];
                                    $label = $serviceLabels[$project->service_type] ?? [$project->service_type, 'bg-gray-100 text-gray-600'];
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $label[1] }}">
                                    {{ $label[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusLabels = [
                                        'active'    => ['جاري', 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                                        'completed' => ['مكتمل', 'text-indigo-600 bg-indigo-50 border-indigo-100'],
                                        'draft'     => ['مسودة', 'text-gray-400 bg-gray-50 border-gray-100'],
                                    ];
                                    $status = $statusLabels[$project->status] ?? [$project->status, 'text-gray-400 bg-gray-50 border-gray-100'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $status[1] }}">
                                    {{ $status[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-[11px] text-gray-500 font-medium">
                                    {{ $project->start_date ? (is_string($project->start_date) ? $project->start_date : $project->start_date->format('Y/m/d')) : '---' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ $project->show_route }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="التفاصيل / العرض">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ $project->edit_route }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400">لا توجد مشاريع مضافة حالياً</p>
                                    <a href="{{ route('project_manager.projects.create') }}" class="text-xs text-indigo-600 font-bold hover:underline">أضف أول مشروع لك</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
