@extends('layouts.app')
{{-- resources/views/entry/services/blank.blade.php --}}

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="flex flex-col items-center justify-center min-h-[400px] bg-white rounded-3xl border border-gray-100 shadow-sm p-12 text-center">
    
    <div class="w-24 h-24 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-6 animate-pulse">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>

    <h2 class="text-2xl font-black text-gray-800 mb-3">{{ $title }}</h2>
    
    <p class="text-gray-400 max-w-sm mx-auto leading-relaxed mb-8">
        هذه الخدمة قيد التطوير حالياً وسيتم برمجتها بشكل كامل قريباً ضمن المخطط التطويري للنظام.
    </p>

    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm transition-all">
            العودة للسابق
        </a>
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm transition-all shadow-lg shadow-indigo-600/20">
            الرئيسية
        </a>
    </div>

</div>
@endsection
