@extends('layouts.app')

@section('title', 'بيانات الجلسة الجماعية')
@section('page-title', 'إدخال حضور جلسة جماعية')

@section('content')
<div class="max-w-6xl mx-auto text-right font-medium" dir="rtl" x-data="attendanceHandler()">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-800">قائمة المستفيدين / الحضور</h3>
                <p class="text-[11px] text-gray-500 mt-1">مشروع: {{ $group_support_session->project_name }} | ممول: {{ $group_support_session->funder }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button type="button" @click="addAttendee()" class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-xl font-bold hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة حضور
                </button>
                <a href="{{ route('data_entry.services.group_support_session.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-bold transition-all flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    العودة
                </a>
            </div>
        </div>
        
        <form action="{{ route('data_entry.services.group_support_session.update', $group_support_session) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            
            @include('entry.services.group_support_session.partials.form_details', ['group_support_session' => $group_support_session])

            <div class="flex items-center gap-3 pt-6 px-2 mt-4 border-t border-gray-50">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/20">
                    حفظ وإرسال القائمة للمراجعة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
