@extends('layouts.auth')

@section('title', '500 - خطأ في النظام')

@section('content')
<div class="text-center p-8">
    <div class="mb-6 flex justify-center">
        <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
    </div>
    <h1 class="text-6xl font-black text-slate-800 mb-4 tracking-tighter">500</h1>
    <h2 class="text-xl font-bold text-slate-700 mb-6 font-arabic">حدث خطأ داخلي في الخادم</h2>
    <p class="text-slate-400 mb-8 max-w-sm mx-auto font-arabic leading-relaxed">
        حدث شيء غير متوقع من طرفنا. يجري حالياً رصد المشكلة ومعالجتها من قبل الفريق التقني.
    </p>
    <a href="{{ url('/') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 font-arabic">
        تحديث الصفحة
    </a>
</div>
@endsection
