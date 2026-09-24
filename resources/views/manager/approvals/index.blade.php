@extends('layouts.app')

@section('title', 'إدارة الموافقات')
@section('page-title', 'طلبات بانتظار المراجعة')

@section('content')
<div x-data="{ 
    showRejectModal: false, 
    rejectType: '', 
    rejectId: '', 
    rejectReason: '',
    confirmReject(type, id) {
        this.rejectType = type;
        this.rejectId = id;
        this.showRejectModal = true;
    }
}" class="space-y-6 text-right font-medium" dir="rtl">

    {{-- Tabs / Navigation for types --}}
    <div class="flex items-center gap-4 border-b border-gray-100 pb-1">
        <h3 class="text-sm font-bold text-gray-800 border-b-2 border-indigo-600 pb-3 px-2">كل الطلبات المعلقة</h3>
        <p class="text-xs text-gray-400 mr-auto font-bold font-medium font-bold">
            إجمالي الطلبات: {{ $pendingApprovals->total() }}
        </p>
    </div>

    {{-- List of Pendings --}}
    <div class="space-y-4">
        @forelse($pendingApprovals as $item)
            @php $type = $item->type_key; @endphp
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-indigo-100 transition-colors">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded font-bold">
                                {{ match($type) {
                                    'training' => 'تدريب',
                                    'economic_empowerment' => 'تمكين اقتصادي',
                                    'awareness_workshop' => 'ورش توعوية',
                                    'legal_consultation' => 'استشارة قانونية',
                                    'psychological_consultation' => 'استشارة نفسية',
                                    'judicial_representation' => 'تمثيل قضائي',
                                    'legal_representation' => 'تمثيل قانوني',
                                    'mediation' => 'وساطة',
                                    'individual_support_session' => 'جلسات فردية',
                                    'group_support_session' => 'جلسات جماعية',
                                    default => $type
                                } }}
                            </span>
                            <a href="{{ route('project_manager.approvals.show', ['type' => $type, 'id' => $item->id]) }}" class="text-base font-bold text-gray-800 hover:text-indigo-600 transition-colors">
                                {{ $item->full_name ?? $item->beneficiary_name ?? $item->owner_name ?? $item->project_name ?? $item->name }}
                            </a>
                        </div>
                        @if($item->parent)
                            <p class="text-[11px] text-amber-600 font-bold mb-1">تابعة لـ: {{ $item->parent->project_name ?? $item->parent->name }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mb-2">ممول: <span class="font-bold text-gray-700 font-bold">{{ $item->funder ?? $item->funding_agency }}</span></p>
                        <div class="flex items-center gap-4 text-[11px] text-gray-400 font-bold">
                            <span>بواسطة: <b>{{ $item->submitter->name }}</b></span>
                            <span>التاريخ: <b>{{ $item->created_at->format('Y/m/d H:i') }}</b></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('project_manager.approvals.approve', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/20 transition-all font-bold">موافقة</button>
                    </form>
                    <button @click="confirmReject('{{ $type }}', {{ $item->id }})" class="bg-white border border-rose-100 text-rose-600 hover:bg-rose-50 px-5 py-2 rounded-xl text-sm font-bold transition-all font-bold">رفض</button>
                </div>
            </div>
        @empty
            <div class="bg-white py-20 rounded-2xl border border-gray-100 flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="text-lg font-bold text-gray-800">لا يوجد طلبات معلقة حالياً</h4>
                <p class="text-gray-400 text-sm font-bold">عمل رائع! لقد قمت بمراجعة كافة الإدخالات.</p>
            </div>
        @endforelse

        @if($pendingApprovals->hasPages())
            <div class="px-6 py-4 bg-white rounded-2xl border border-gray-100 mt-4">
                {{ $pendingApprovals->links() }}
            </div>
        @endif
    </div>

    {{-- Rejection Modal --}}
    <div x-show="showRejectModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden font-medium" @click.away="showRejectModal = false">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-2">رفض الطلب</h3>
                <p class="text-sm text-gray-500 mb-6 font-bold">يرجى كتابة سبب الرفض ليتمكن مدخل البيانات من تصحيحه.</p>

                <form :action="`{{ url('project_manager/approvals') }}/${rejectType}/${rejectId}/reject`" method="POST">
                    @csrf
                    <textarea name="reason" rows="4" required placeholder="مثلاً: البيانات الشخصية غير كاملة..."
                              class="w-full rounded-2xl border-gray-200 focus:border-rose-500 focus:ring-rose-200 shadow-sm transition-all mb-6 text-sm font-medium"></textarea>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-rose-600/20 transition-all">تأكيد الرفض</button>
                        <button type="button" @click="showRejectModal = false" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold py-3 rounded-2xl transition-all font-bold">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
