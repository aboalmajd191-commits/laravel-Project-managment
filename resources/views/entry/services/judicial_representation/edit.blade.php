@extends('layouts.app')

@section('title', 'تعبئة قضية تمثيل قضائي')
@section('page-title', 'إدخال بيانات قضية تمثيل قضائي')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium font-medium" dir="rtl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between font-bold bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-800">بيانات المستفيد والقضية</h3>
                <p class="text-[11px] text-gray-500 mt-1 font-bold">مشروع: {{ $judicial_representation->project_name }} | ممول: {{ $judicial_representation->funding_agency }}</p>
            </div>
            <a href="{{ route('data_entry.services.judicial_representation.index') }}" class="text-sm text-gray-500 font-bold hover:text-indigo-600 transition-all flex items-center gap-1 font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                العودة
            </a>
        </div>
        
        <form action="{{ route('data_entry.services.judicial_representation.update', $judicial_representation) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8 font-medium">
            @csrf @method('PUT')
            
            @include('entry.services.judicial_representation.partials.form_details', ['item' => $judicial_representation])

            <div class="flex items-center gap-3 pt-6 border-t border-gray-50 font-bold">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20 font-bold">
                    {{ $judicial_representation->parent_id ? 'تحديث البيانات' : 'حفظ وإرسال للمراجعة' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
