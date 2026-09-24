@extends('layouts.app')

@section('title', 'تعبئة استشارة نفسية')
@section('page-title', 'إدخال بيانات استشارة نفسية وتوجيه')

@section('content')
<div class="max-w-5xl mx-auto text-right font-medium" dir="rtl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden font-medium">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-800">بيانات المستفيد والاستشارة النفسية</h3>
                <p class="text-[11px] text-gray-500 mt-1">المشروع: {{ $psychological_consultation->project_name }} | الممول: {{ $psychological_consultation->funding_agency }}</p>
            </div>
            <a href="{{ route('data_entry.services.psychological_consultation.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                العودة
            </a>
        </div>
        
        <form action="{{ route('data_entry.services.psychological_consultation.update', $psychological_consultation) }}" method="POST" class="p-6 space-y-8 font-medium">
            @csrf @method('PUT')
            
            @include('entry.services.psychological_consultation.partials.form_details', ['item' => $psychological_consultation])

            <div class="flex items-center gap-3 pt-6 border-t border-gray-50">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                    {{ $psychological_consultation->parent_id ? 'تحديث البيانات' : 'حفظ وإرسال للمراجعة' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
