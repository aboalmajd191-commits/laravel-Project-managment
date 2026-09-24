@extends('layouts.auth')

@section('title', '404 - غير موجود')

@section('content')
<div class="text-center p-8">
    <div class="mb-6 flex justify-center">
        <div class="w-24 h-24 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
    <h1 class="text-6xl font-black text-slate-800 mb-4 tracking-tighter">404</h1>
    <h2 class="text-xl font-bold text-slate-700 mb-6 font-arabic">عذراً، الصفحة غير موجودة</h2>
    <p class="text-slate-400 mb-8 max-w-sm mx-auto font-arabic leading-relaxed">
        الرابط الذي تحاول الوصول إليه ربما تم حذفه أو تغييره، أو قد يكون العنوان مكتوباً بشكل خاطئ.
    </p>
    <a href="{{ url('/') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 font-arabic">
        العودة للرئيسية
    </a>
</div>
@endsection
