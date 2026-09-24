@extends('layouts.auth')
{{-- resources/views/auth/login.blade.php --}}

@section('title', 'تسجيل الدخول')

@section('content')
<div class="w-full max-w-md">

    {{-- Logo / Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-4 border border-white/20 shadow-xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white">نظام إدارة المشاريع</h1>
        <p class="text-indigo-200 mt-1 text-sm">للمؤسسات غير الربحية</p>
    </div>

    {{-- Card --}}
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-2xl p-8">

        <h2 class="text-xl font-semibold text-white text-center mb-6">تسجيل الدخول إلى حسابك</h2>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="mb-5 bg-red-500/20 border border-red-400/40 rounded-xl p-4">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-200 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-indigo-100 mb-1.5">
                    البريد الإلكتروني
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="admin@ngo.ps"
                        class="w-full bg-white/10 border border-white/20 rounded-xl pr-10 pl-4 py-3 text-white placeholder-indigo-300
                               focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent
                               transition duration-200 @error('email') border-red-400 @enderror"
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-indigo-100 mb-1.5">
                    كلمة المرور
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••••"
                        class="w-full bg-white/10 border border-white/20 rounded-xl pr-10 pl-4 py-3 text-white placeholder-indigo-300
                               focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent
                               transition duration-200 @error('password') border-red-400 @enderror"
                    >
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2">
                <input id="remember" type="checkbox" name="remember"
                       class="w-4 h-4 rounded bg-white/10 border-white/30 text-indigo-500 focus:ring-indigo-400 cursor-pointer">
                <label for="remember" class="text-sm text-indigo-200 cursor-pointer select-none">
                    تذكرني
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700
                           text-white font-semibold py-3 px-4 rounded-xl
                           transition duration-200 shadow-lg shadow-indigo-900/40
                           focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-transparent
                           flex items-center justify-center gap-2 mt-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                دخول
            </button>
        </form>
    </div>

    <p class="text-center text-indigo-300/60 text-xs mt-6">
        نظام إدارة المشاريع &copy; {{ date('Y') }}
    </p>
</div>
@endsection
