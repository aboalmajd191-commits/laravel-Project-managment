@extends('layouts.app')

@section('title', 'خدمات الجلسات الجماعية')
@section('page-title', 'سجل الجلسات الجماعية (الدعم الجماعي)')

@section('content')
<div class="space-y-6 text-right" dir="rtl">
    <div class="flex items-center justify-between font-medium">
        <p class="text-sm text-gray-500 font-bold">تعبئة البيانات التفصيلية لجلسات الدعم الجماعي والأنشطة المشتركة</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممول</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $session->project_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $session->funder }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500">
                                {{ $session->start_date?->format('Y/m/d') ?: 'غير محدد' }} - {{ $session->end_date?->format('Y/m/d') ?: 'غير محدد' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($session->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $session->children_count }} قائمة حضور
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات سابقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.group_support_session.edit', $session) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        إدراج جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold font-medium font-bold">لا توجد سجلات متاحة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 font-medium">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
