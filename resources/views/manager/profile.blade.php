@extends('layouts.app')
{{-- resources/views/manager/profile.blade.php --}}

@section('title', 'الملف الشخصي')
@section('page-title', 'بياناتي الشخصية')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col md:flex-row">
        <div class="bg-slate-900 p-12 flex flex-col items-center justify-center text-white shrink-0">
            <div class="w-24 h-24 rounded-3xl bg-indigo-600 flex items-center justify-center text-4xl font-bold uppercase mb-4 shadow-xl shadow-indigo-600/40">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
            <span class="text-xs bg-white/10 px-3 py-1 rounded-full mt-2 text-indigo-300 font-bold tracking-widest uppercase">
                {{ auth()->user()->role }}
            </span>
        </div>
        
        <div class="p-8 flex-1 grid grid-cols-1 sm:grid-cols-2 gap-8 content-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">البريد الإلكتروني</p>
                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->email }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">رقم الهوية</p>
                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->id_number ?: '---' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">رقم الجوال</p>
                <p class="text-sm font-bold text-gray-800 font-mono">{{ auth()->user()->phone ?: '---' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">تاريخ الانضمام</p>
                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->created_at->format('Y/m/d') }}</p>
            </div>
            <div class="sm:col-span-2 pt-4 border-t border-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">العنوان</p>
                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->address ?: 'غير مسجل' }}</p>
            </div>
        </div>
    </div>

    {{-- Change Password (Placeholder UI for now) --}}
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h4 class="text-lg font-bold text-gray-800">تغيير كلمة المرور</h4>
        </div>
        
        <p class="text-xs text-gray-400 leading-relaxed max-w-md">
            لضمان أمان حسابك، يمكنك تغيير كلمة المرور بشكل دوري. في حال واجهت أي مشكلة، يرجى التواصل مع مسؤول النظام.
        </p>
        
        <div class="mt-6">
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-bold transition-all">تفعيل تحديث الأمان</button>
        </div>
    </div>

</div>
@endsection
