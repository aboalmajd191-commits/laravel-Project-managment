@extends('layouts.app')
{{-- resources/views/entry/services/training/edit.blade.php --}}

@section('title', 'تعبئة بيانات التدريب')
@section('page-title', 'تعبئة قائمة الحضور والبيانات التفصيلية')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">بيانات النشاط المعتمدة</p>
                <h3 class="text-sm font-bold text-slate-800">{{ $training->name }} — الممول: {{ $training->funder }}</h3>
            </div>
        </div>
        <div class="text-left">
            <p class="text-[10px] text-indigo-400 font-bold">المستهدف: {{ $training->beneficiary_count }}</p>
            <p class="text-[10px] text-indigo-400 font-bold">{{ $training->start_date->format('Y/m/d') }} - {{ $training->end_date->format('Y/m/d') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <form action="{{ route('data_entry.services.training.update', $training) }}" method="POST" class="p-8 space-y-10">
            @csrf @method('PUT')

            {{-- Use the shared partial --}}
            @include('entry.services.training.partials.form_details', ['training' => $training])

            <div class="pt-8 flex items-center justify-end gap-3 border-t border-gray-50">
                <a href="{{ route('data_entry.services.training.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">رجوع</a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ وإرسال للمراجعة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
