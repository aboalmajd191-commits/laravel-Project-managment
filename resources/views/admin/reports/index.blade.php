@extends('layouts.app')
{{-- resources/views/admin/reports/index.blade.php --}}

@section('title', 'التقارير والإحصائيات')
@section('page-title', 'تصدير بيانات المشاريع')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    {{-- Trainings Report --}}
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">تقرير التدريبات</h3>
                <p class="text-xs text-gray-400">تصدير كافة المحاضرات والورش المنفذة</p>
            </div>
        </div>

        <form action="{{ route('admin.reports.trainings') }}" method="GET" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 mb-2 block">فلترة بالمشروع</label>
                <select name="project_id" class="w-full">
                    <option value="">كل المشاريع</option>
                    @foreach($projects->where('service_type', 'training') as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 mb-2 block">من تاريخ</label>
                    <input type="date" name="start_date" class="w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 mb-2 block">إلى تاريخ</label>
                    <input type="date" name="end_date" class="w-full">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2 mt-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                تحميل (CSV/Excel)
            </button>
        </form>
    </div>

    {{-- Economic Projects Report --}}
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">تقرير التمكين الاقتصادي</h3>
                <p class="text-xs text-gray-400">تصدير بيانات المستفيدين من المنح والمشاريع</p>
            </div>
        </div>

        <form action="{{ route('admin.reports.economics') }}" method="GET" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 mb-2 block">فلترة بالمشروع الممول</label>
                <select name="project_id" class="w-full">
                    <option value="">كل المشاريع</option>
                    @foreach($projects->where('service_type', 'economic_empowerment') as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl text-[11px] text-gray-400 leading-relaxed italic">
                * ملاحظة: يتم تصدير البيانات المعتمدة فقط من قبل مديري المشاريع. سيتم تحميل ملف CSV متوافق مع كافة برامج الجداول الحسابية.
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 mt-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                بدء التصدير
            </button>
        </form>
    </div>

</div>
@endsection
