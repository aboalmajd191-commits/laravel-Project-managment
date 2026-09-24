@extends('layouts.app')

@section('title', 'سجل جلساتي الفردية')
@section('page-title', 'الجلسات الفردية')

@section('content')
<div class="space-y-6 text-right" dir="rtl">

    {{-- ─── Section 1: Parent Projects (Available for new entry) ─── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-800">المشاريع المتاحة للإدخال</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">اضغط على "إضافة جلسة" لإرسال بيانات جلسة فردية جديدة</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم المشروع / الممول</th>
                        <th class="px-6 py-4 text-center">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center">إدخالاتي</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($parentSessions as $session)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $session->project_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $session->funder }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500">
                                {{ $session->start_date?->format('Y/m/d') }} - {{ $session->end_date?->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($session->my_entries_count ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-indigo-600 bg-indigo-50 border-indigo-100">
                                        {{ $session->my_entries_count }} جلسة
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border text-gray-400 bg-gray-50 border-gray-100">
                                        لا يوجد
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('data_entry.services.individual_support_session.edit', $session) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    إضافة جلسة
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">لا توجد مشاريع متاحة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parentSessions->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 font-medium">
                {{ $parentSessions->links() }}
            </div>
        @endif
    </div>

    {{-- ─── Section 2: My Submitted Entries ─── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-gray-800">جلساتي المُقدَّمة</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">يمكنك تعديل الجلسات غير المقبولة بعد</p>
            </div>
            
            @php
                $hasDrafts = $mySubmissions->contains('approval_status', 'draft');
            @endphp
            @if($hasDrafts)
            <form action="{{ route('data_entry.services.individual_support_session.submit_all_drafts') }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[12px] font-bold shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    إرسال جميع الجلسات غير المعتمدة للموافقة
                </button>
            </form>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-right">المشروع</th>
                        <th class="px-6 py-4 text-center">التاريخ</th>
                        <th class="px-6 py-4 text-center">حالة المراجعة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($mySubmissions as $sub)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $sub->parent?->project_name ?? $sub->project_name }}</p>
                                <p class="text-[11px] text-gray-400">{{ $sub->case_code ? 'كود: '.$sub->case_code : '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500">
                                {{ $sub->created_at->format('Y/m/d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusMap = [
                                        'approved' => ['label' => 'مقبول', 'class' => 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                                        'pending'  => ['label' => 'قيد المراجعة', 'class' => 'text-amber-600 bg-amber-50 border-amber-100'],
                                        'rejected' => ['label' => 'مرفوض', 'class' => 'text-rose-600 bg-rose-50 border-rose-100'],
                                        'draft'    => ['label' => 'مسودة', 'class' => 'text-slate-600 bg-slate-50 border-slate-200'],
                                    ];
                                    $s = $statusMap[$sub->approval_status] ?? ['label' => $sub->approval_status, 'class' => 'text-gray-400 bg-gray-50 border-gray-100'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                @if($sub->approval_status !== 'approved')
                                    <a href="{{ route('data_entry.services.individual_support_session.edit', $sub) }}"
                                       class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all inline-block" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="p-2 text-gray-300 cursor-not-allowed inline-block" title="لا يمكن تعديل طلب مقبول">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">لم تُقدِّم أي جلسات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mySubmissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 font-medium">
                {{ $mySubmissions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
