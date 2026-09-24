@extends('layouts.app')
{{-- resources/views/manager/services/economic/index.blade.php --}}

@section('title', 'مشاريع التمكين الاقتصادي')
@section('page-title', 'سجل مشاريع التمكين')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">إدارة ومتابعة مشاريع التمكين الاقتصادي</p>
        <a href="{{ route('project_manager.services.economic_empowerment.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إضافة مشروع تمكيني جديد
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">اسم المشروع / المنسق</th>

                        <th class="px-6 py-4 text-center">القيمة الإجمالية</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($economics as $economic)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                             <td class="px-6 py-4">
                                 <p class="text-sm font-bold text-gray-800">{{ $economic->project_name }}</p>
                                 <div class="flex items-center gap-2 mt-0.5">
                                     @if($economic->parent_project)
                                         <span class="text-[10px] bg-slate-50 text-slate-500 px-1.5 py-0.5 rounded font-bold border border-slate-100">
                                             المشروع: {{ $economic->parent_project }}
                                         </span>
                                     @endif
                                     <span class="text-[11px] text-gray-400">{{ $economic->coordinator_name }} ({{ $economic->funder }})</span>
                                 </div>
                             </td>

                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                ${{ number_format($economic->total_grant_value, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('project_manager.projects.update_status', $economic->id) }}" method="POST" class="inline-flex gap-1 font-bold">
                                    @csrf
                                    <input type="hidden" name="type" value="economic_empowerment">
                                    <button name="status" value="active" class="px-2 py-1 text-[10px] rounded bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-all font-bold {{ $economic->status == 'active' ? 'ring-2 ring-emerald-500 ring-offset-1' : '' }}">نشط</button>
                                    <button name="status" value="completed" class="px-2 py-1 text-[10px] rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition-all font-bold {{ $economic->status == 'completed' ? 'ring-2 ring-blue-500 ring-offset-1' : '' }}">مكتمل</button>
                                    <button name="status" value="draft" class="px-2 py-1 text-[10px] rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all font-bold {{ $economic->status == 'draft' ? 'ring-2 ring-gray-500 ring-offset-1' : '' }}">مسودة</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('project_manager.services.economic_empowerment.show', $economic) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="التفاصيل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.economic_empowerment.edit', $economic) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="تعديل البيانات الأساسية">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('project_manager.services.economic_empowerment.edit', [$economic, 'tab' => 'entry']) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="إضافة مستفيد">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold">لا يوجد مشاريع تمكين حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
