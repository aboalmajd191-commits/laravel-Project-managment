@extends('layouts.app')
{{-- resources/views/manager/services/training/index.blade.php --}}

@section('title', 'إدارة التدريبات')
@section('page-title', 'سجل الأنشطة التدريبية')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">متابعة وإدارة كافة التدريبات المخطط لها والمنفذة</p>
        <a href="{{ route('project_manager.services.training.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إنشاء نشاط تدريبي جديد
        </a>
    </div>

    {{-- Trainings Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">التدريب / العنوان</th>

                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">المستهدف</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($trainings as $training)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                             <td class="px-6 py-4">
                                 <p class="text-sm font-bold text-gray-800">{{ $training->name }}</p>
                                 <div class="flex items-center gap-2 mt-0.5">
                                     @if($training->parent_project)
                                         <span class="text-[10px] bg-slate-50 text-slate-500 px-1.5 py-0.5 rounded font-bold border border-slate-100">
                                             المشروع: {{ $training->parent_project }}
                                         </span>
                                     @endif
                                     <span class="text-[11px] text-gray-400">{{ $training->funder }}</span>
                                 </div>
                             </td>

                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold">
                                {{ $training->start_date->format('Y/m/d') }} - {{ $training->end_date->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $training->children_count }} تنفيذ
                            </td>
                            <td class="px-6 py-4 text-center font-bold">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] border {{ $training->status == 'active' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-gray-400 bg-gray-50 border-gray-100' }}">
                                    {{ $training->status == 'active' ? 'نشط' : 'مسودة' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('project_manager.services.training.show', $training) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="التفاصيل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.training.edit', $training) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" /></svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.training.edit', ['training' => $training, 'tab' => 'entry']) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="إضافة بيانات تنفيذ">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <p class="text-sm font-bold text-gray-400">لا توجد أنشطة تدريبية حالياً</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
