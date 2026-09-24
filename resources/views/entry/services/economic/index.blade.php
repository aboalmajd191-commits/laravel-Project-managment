@extends('layouts.app')
{{-- resources/views/entry/services/economic/index.blade.php --}}

@section('title', 'التمكين الاقتصادي')
@section('page-title', 'سجل المشاريع الصغيرة للأسرة')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">تعبئة بيانات المستفيدين لمشاريع التمكين الاقتصادي المخصصة</p>
    </div>

    {{-- Economic Projects Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">صاحب المشروع</th>
                        <th class="px-6 py-4">اسم المشروع الصغير</th>
                        <th class="px-6 py-4 text-center">المحافظة</th>
                        <th class="px-6 py-4 text-center">التكلفة</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($economics as $econ)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $econ->owner_name ?: '— بانتظار البيانات —' }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">هويّة: {{ $econ->id_number ?: '---' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">
                                    {{ $econ->project_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ $econ->governorate ?: '---' }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                ${{ number_format($econ->grant_value ?? 0, 2) }}
                                <p class="text-[9px] text-gray-400 font-medium leading-none mt-1">لـ {{ $econ->individuals_count ?? 0 }} أفراد</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($econ->children_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        تم إدراج {{ $econ->children_count }} مشروع
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد إدخالات سابقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('data_entry.services.economic_empowerment.edit', $econ) }}" 
                                       class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        {{ ($econ->children_count ?? 0) > 0 ? 'إدراج جديد' : 'إضافة إدخال جديد' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <p class="text-sm font-bold text-gray-400">لا توجد سجلات مخصصة لك حالياً</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($economics->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $economics->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
