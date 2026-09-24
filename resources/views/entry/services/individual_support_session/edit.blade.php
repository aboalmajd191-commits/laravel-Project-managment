@extends('layouts.app')

@section('title', 'تعديل جلسة فردية')
@section('page-title', 'تعديل بيانات جلسة فردية')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium" dir="rtl">

    {{-- Context banner --}}
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </div>
            <div>
                <p class="text-xs text-amber-600 font-bold uppercase tracking-wider">تعديل جلسة مُقدَّمة</p>
                <h3 class="text-sm font-bold text-slate-800">{{ $individual_support_session->parent?->project_name ?? $individual_support_session->project_name }}</h3>
            </div>
        </div>
        <a href="{{ route('data_entry.services.individual_support_session.index') }}"
           class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            العودة
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('data_entry.services.individual_support_session.update', $individual_support_session) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')

            @include('entry.services.individual_support_session.partials.form_details', ['individual_support_session' => $individual_support_session, 'item' => $individual_support_session])

            <div class="flex items-center gap-3 pt-6 border-t border-gray-50">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-amber-600 text-white px-10 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-amber-500/20">
                    تحديث البيانات
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
