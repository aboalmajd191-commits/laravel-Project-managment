@extends('layouts.app')
{{-- resources/views/entry/services/economic/edit.blade.php --}}

@section('title', 'تعبئة بيانات المستفيد')
@section('page-title', 'تعبئة بيانات المنحة والمستفيد')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">مشروع تمكين معتمد</p>
                <h3 class="text-sm font-bold text-slate-800">{{ $economic->project_name }} — المنحة: ${{ number_format($economic->total_grant_value) }}</h3>
            </div>
        </div>
        <div class="text-left font-arabic">
            <p class="text-[10px] text-indigo-400 font-bold">المنسق: {{ $economic->coordinator_name }}</p>
            <p class="text-[10px] text-indigo-400 font-bold">الممول: {{ $economic->funder }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden text-right">
        <form action="{{ route('data_entry.services.economic_empowerment.update', $economic) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
            @csrf @method('PUT')

            @include('entry.services.economic.partials.form_details', ['economic' => $economic])

            <div class="pt-8 flex items-center justify-end gap-3 border-t border-gray-50">
                <a href="{{ route('data_entry.services.economic_empowerment.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">رجوع</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">حفظ وإرسال للمراجعة</button>
            </div>
        </form>
    </div>
</div>
@endsection
