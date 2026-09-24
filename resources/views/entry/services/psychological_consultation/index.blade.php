@extends('layouts.app')

@section('title', 'خدمات الاستشارات النفسية')
@section('page-title', 'سجل الاستشارات النفسية')

@section('content')
<div class="space-y-6 text-right font-medium" dir="rtl">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 font-medium font-medium">تعبئة البيانات التفصيلية للاستشارات النفسية المخصصة</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="overflow-x-auto font-medium">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممولة</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($consultations as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $item->project_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5 font-bold font-medium">{{ $item->funding_agency }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold font-bold font-bold font-bold">
                                {{ $item->start_date?->format('Y/m/d') }} - {{ $item->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($item->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $item->children_count }} استشارة
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات سابقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.psychological_consultation.edit', $item) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors shadow-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        إضافة استشارة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold font-medium font-bold">لا توجد استشارات نفسية متاحة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($consultations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 font-medium font-bold">
                {{ $consultations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
