@extends('layouts.app')

@section('title', 'إضافة جلسة فردية جديدة')
@section('page-title', 'إضافة بيانات جلسة فردية')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium" dir="rtl">

    {{-- Context banner --}}
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">مشروع معتمد — إضافة جلسة جديدة</p>
                <h3 class="text-sm font-bold text-slate-800">{{ $parent->project_name }} — الممول: {{ $parent->funder }}</h3>
            </div>
        </div>
        <div class="text-left">
            <p class="text-[10px] text-indigo-400 font-bold">{{ $parent->start_date?->format('Y/m/d') }} - {{ $parent->end_date?->format('Y/m/d') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-base font-bold text-gray-800">بيانات الحالة والجلسات</h3>
            <a href="{{ route('data_entry.services.individual_support_session.index') }}"
               class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                العودة
            </a>
        </div>

        <form action="{{ route('data_entry.services.individual_support_session.store_child', $parent) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            @php
                // Pass an empty item so the partial works correctly
                $individual_support_session = new \App\Models\IndividualSupportSession();
                $individual_support_session->setRelation('details', collect());
            @endphp

            @include('entry.services.individual_support_session.partials.form_details', ['individual_support_session' => $individual_support_session, 'item' => $individual_support_session])

            <div class="flex items-center gap-3 pt-6 border-t border-gray-50">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ وإرسال للمراجعة
                </button>
                <a href="{{ route('data_entry.services.individual_support_session.index') }}"
                   class="text-sm text-gray-400 hover:text-gray-600 px-4 font-bold">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
