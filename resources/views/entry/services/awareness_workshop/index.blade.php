@extends('layouts.app')

@section('title', 'خدمات الورش التوعوية')
@section('page-title', 'سجل الورش التوعوية')

@section('content')
<div class="space-y-6 text-right" dir="rtl">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">تعبئة البيانات التفصيلية للورش التوعوية المخصصة</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممول</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">المستفيدين</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($workshops as $workshop)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $workshop->project_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $workshop->funder }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500">
                                {{ $workshop->start_date?->format('Y/m/d') }} - {{ $workshop->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $workshop->total_beneficiaries }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($workshop->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $workshop->children_count }} بيان
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات سابقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.awareness_workshop.edit', $workshop) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        {{ ($workshop->children_count ?? 0) > 0 ? 'إدراج جديد' : 'إضافة إدخال جديد' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-gray-400 font-bold">لا توجد ورش عمل متاحة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($workshops->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $workshops->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
