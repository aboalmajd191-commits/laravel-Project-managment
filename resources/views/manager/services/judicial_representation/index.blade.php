@extends('layouts.app')

@section('title', 'إدارة التمثيل القضائي')
@section('page-title', 'سجل قضايا التمثيل القضائي')

@section('content')
<div class="space-y-6 text-right font-medium" dir="rtl">
    <div class="flex items-center justify-between font-bold">
        <p class="text-sm text-gray-500 font-medium">متابعة وإدارة قضايا التمثيل القضائي</p>
        <a href="{{ route('project_manager.services.judicial_representation.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20 shadow-sm font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إضافة سجل قضائي جديد
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="overflow-x-auto">
            <table class="w-full font-medium">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممول</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium font-medium">
                    @forelse($representations as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $item->project_name }}</p>
                                <span class="text-[11px] text-gray-400 font-bold">{{ $item->funder ?? $item->funding_agency }}</span>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold">
                                {{ $item->start_date?->format('Y/m/d') }} - {{ $item->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-gray-700">
                                {{ $item->children_count }} تنفيذ
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] border {{ $item->status == 'active' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-gray-400 bg-gray-50 border-gray-100' }}">
                                    {{ $item->status == 'active' ? 'نشط' : 'مسودة' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('project_manager.services.judicial_representation.show', $item) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="التفاصيل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.judicial_representation.edit', $item) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" /></svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.judicial_representation.edit', [$item, 'tab' => 'entry']) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="إضافة بيانات تنفيذ">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold font-medium font-bold">لا توجد سجلات حالياً</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($representations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 font-medium font-bold">
                {{ $representations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
