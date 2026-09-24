<!DOCTYPE html>
{{-- resources/views/layouts/auth.blade.php --}}
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تسجيل الدخول') — نظام إدارة المشاريع</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-indigo-600 from-indigo-900 via-indigo-800 to-emerald-800 flex items-center justify-center p-4">

    @yield('content')

</body>
</html>
