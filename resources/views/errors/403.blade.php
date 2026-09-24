@extends('layouts.auth')

@section('title', '403 - غير مصرح')

@section('content')
<div class="text-center p-8">
    <div class="mb-6 flex justify-center">
        <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
    </div>
    <h1 class="text-6xl font-black text-slate-800 mb-4 tracking-tighter">403</h1>
    <h2 class="text-xl font-bold text-slate-700 mb-6 font-arabic">عذراً، لا تمتلك الصلاحية المطلوبة</h2>
    <p class="text-slate-400 mb-8 max-w-sm mx-auto font-arabic leading-relaxed">
        يبدو أنك تحاول الوصول لصفحة غير مخصصة لمستوى صلاحيتك الحالية. في حال كان هذا خطأ، يرجى التواصل مع المدير.
    </p>
    <a href="{{ url('/') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 font-arabic">
        العودة للرئيسية
    </a>
</div>
@endsection
