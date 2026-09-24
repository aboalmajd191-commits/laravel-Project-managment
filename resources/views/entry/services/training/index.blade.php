@extends('layouts.app')
{{-- resources/views/entry/services/training/index.blade.php --}}

@section('title', 'خدمات التدريب')
@section('page-title', 'سجل التدريبات والمحاضرات')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">تعبئة البيانات التفصيلية للتدريبات والأنشطة المخصصة</p>
    </div>

    {{-- Trainings Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">التدريب / العنوان</th>

                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">المستفيدين</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($trainings as $training)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $training->name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $training->funder }}</p>
                            </td>

                            <td class="px-6 py-4 text-center text-[11px] text-gray-500">
                                {{ $training->start_date->format('Y/m/d') }} - {{ $training->end_date->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $training->beneficiary_count }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($training->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $training->children_count }} بيان
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات سابقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.training.edit', $training) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        {{ ($training->children_count ?? 0) > 0 ? 'إدراج جديد' : 'إضافة إدخال جديد' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400">لا توجد سجلات متاحة حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trainings->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
