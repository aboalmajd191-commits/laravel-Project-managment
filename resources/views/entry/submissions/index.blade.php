@extends('layouts.app')

@section('title', 'عمليات الإدخال الخاصة بي')
@section('page-title', 'سجل مساهماتي')

@section('content')
<div class="space-y-6 text-right" dir="rtl">

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold mb-1">إجمالي الإدخالات</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold mb-1">تمت الموافقة</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $stats['approved'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 font-bold mb-1">قيد المراجعة</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden min-h-[400px]">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">تفاصيل كافة الإدخالات</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-[13px] font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-right">عنوان الإدخال</th>
                        <th class="px-6 py-4 text-center">الخدمة</th>
                        <th class="px-6 py-4 text-center">التاريخ</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($submissions as $item)
                        <tr class="hover:bg-indigo-50/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    @if($item->service_type === 'training' && !empty($item->activity_name))
                                        <span class="text-sm font-bold text-slate-800">{{ $item->activity_name }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold mt-1">الخدمة: {{ $item->name }}</span>
                                    @else
                                        <span class="text-sm font-bold text-slate-800">{{ $item->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $labels = [
                                        'training' => 'تدريب',
                                        'economic_empowerment' => 'تمكين اقتصادي',
                                        'awareness_workshop' => 'ورشة عمل',
                                        'legal_consultation' => 'استشارة قانونية',
                                        'psychological_consultation' => 'استشارة نفسية',
                                        'judicial_representation' => 'تمثيل قضائي',
                                        'legal_representation' => 'تمثيل قانوني',
                                        'mediation' => 'وساطة',
                                        'individual_support_session' => 'جلسة فردية',
                                        'group_support_session' => 'جلسة جماعية'
                                    ];
                                @endphp
                                <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-[10px] font-bold">
                                    {{ $labels[$item->service_type] ?? $item->service_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-[11px] text-gray-500 font-bold">
                                {{ $item->created_at->format('Y/m/d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColor = match($item->approval_status) {
                                        'approved' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        'rejected' => 'text-rose-600 bg-rose-50 border-rose-100',
                                        'pending'  => 'text-amber-600 bg-amber-50 border-amber-100',
                                        default    => 'text-gray-400 bg-gray-50 border-gray-100'
                                    };
                                    $statusLabel = match($item->approval_status) {
                                        'approved' => 'مقبول',
                                        'rejected' => 'مرفوض',
                                        'pending'  => 'معلق',
                                        default    => $item->approval_status
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                @if($item->approval_status !== 'approved')
                                <a href="{{ $item->edit_url }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all inline-block" title="تعديل">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                    </svg>
                                </a>
                                @else
                                <span class="p-2 text-gray-300 cursor-not-allowed inline-block" title="لا يمكن تعديل طلب مقبول">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <p class="text-sm font-bold">لا توجد عمليات إدخال مسجلة لك حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($submissions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 font-medium">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
