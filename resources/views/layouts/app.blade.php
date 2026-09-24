<!DOCTYPE html>
{{-- resources/views/layouts/app.blade.php --}}
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — نظام إدارة المشاريع</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Tajawal', sans-serif; background: #F8FAFC; }
        /* Thin custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC]">

{{-- ═══════════════════════════════════════════════════════════════
     Outer wrapper — sidebar RIGHT, content LEFT (RTL flex-row-reverse)
═══════════════════════════════════════════════════════════════ --}}
<div class="flex flex-row-reverse min-h-screen">

    {{-- ─── SIDEBAR ─────────────────────────────────────────────── --}}
    @include('components.sidebar')

    {{-- ─── MAIN AREA ───────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 mr-72">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-6 py-3">

                {{-- Page title slot --}}
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-semibold text-slate-800">@yield('page-title', 'لوحة التحكم')</h2>
                    @yield('page-breadcrumb')
                </div>

                {{-- User info + logout --}}
                <div class="flex items-center gap-4">

                    {{-- Role badge --}}
                    @php
                        $roleLabel = match(auth()->user()->role) {
                            'admin'           => ['text' => 'مدير النظام',   'class' => 'bg-indigo-100 text-indigo-700'],
                            'project_manager' => ['text' => 'مدير المشروع', 'class' => 'bg-emerald-100 text-emerald-700'],
                            'data_entry'      => ['text' => 'مدخل البيانات','class' => 'bg-amber-100 text-amber-700'],
                            default           => ['text' => auth()->user()->role, 'class' => 'bg-gray-100 text-gray-600'],
                        };
                    @endphp
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleLabel['class'] }}">
                        {{ $roleLabel['text'] }}
                    </span>

                    {{-- User name --}}
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">
                        {{ auth()->user()->name }}
                    </span>

                    {{-- Logout button --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-red-600 transition-colors duration-150 px-3 py-1.5 rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="hidden sm:inline">خروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 mb-4 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-4 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-700 rounded-xl px-4 py-3 mb-4 shadow-sm">
                    <ul class="list-disc list-inside text-xs font-bold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 px-6 pb-8 pt-2">
            @yield('content')
        </main>

    </div>{{-- /main area --}}
</div>{{-- /flex wrapper --}}

@stack('scripts')
</body>
</html>
