@extends('layouts.app')

@section('title', 'خدمات التمثيل القضائي')
@section('page-title', 'سجل التمثيل القضائي والقضايا')

@section('content')
<div class="space-y-6 text-right font-medium" dir="rtl">
    <div class="flex items-center justify-between font-bold">
        <p class="text-sm text-gray-500 font-medium">تعبئة البيانات التفصيلية لقضايا التمثيل القضائي</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="overflow-x-auto font-medium">
            <table class="w-full font-medium">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممولة</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center font-bold">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($cases as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right font-medium">
                                <p class="text-sm font-bold text-gray-800">{{ $item->project_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5 font-bold">{{ $item->funding_agency }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold">
                                {{ $item->start_date?->format('Y/m/d') }} - {{ $item->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($item->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $item->children_count }} قضية
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.judicial_representation.edit', $item) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors shadow-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        إدراج قضية
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold font-medium font-bold">لا توجد سجلات متاحة حالياً</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cases->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 font-medium font-bold">
                {{ $cases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
