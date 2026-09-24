@extends('layouts.app')
{{-- resources/views/admin/dashboard.blade.php --}}

@section('title', 'لوحة التحكم - الإدارة')
@section('page-title', 'نظرة عامة على النظام')

@section('content')
<div class="space-y-6">

    {{-- ─── Filters Row ────────────────────────────────────────── --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">البحث عن مستفيد (الاسم / الهوية / الجوال)</label>
                    <input type="text" name="beneficiary" value="{{ request('beneficiary') }}" placeholder="ادخل اسم المستفيد أو رقم الهوية أو رقم الجوال..." class="w-full">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">اسم المشروع</label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="بحث باسم المشروع..." class="w-full">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">اسم الممول</label>
                    <input type="text" name="funder" value="{{ request('funder') }}" placeholder="بحث بالممول..." class="w-full">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end pt-2">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">نوع الخدمة</label>
                    <select name="service_type" class="w-full">
                        <option value="">كل الخدمات</option>
                        <option value="training" {{ request('service_type') === 'training' ? 'selected' : '' }}>تدريب وبناء قدرات</option>
                        <option value="awareness_workshop" {{ request('service_type') === 'awareness_workshop' ? 'selected' : '' }}>ورش توعوية</option>
                        <option value="economic_empowerment" {{ request('service_type') === 'economic_empowerment' ? 'selected' : '' }}>التمكين الاقتصادي</option>
                        <option value="legal_consultation" {{ request('service_type') === 'legal_consultation' ? 'selected' : '' }}>استشارة قانونية</option>
                        <option value="psychological_consultation" {{ request('service_type') === 'psychological_consultation' ? 'selected' : '' }}>استشارة نفسية</option>
                        <option value="judicial_representation" {{ request('service_type') === 'judicial_representation' ? 'selected' : '' }}>تمثيل قضائي</option>
                        <option value="individual_support_session" {{ request('service_type') === 'individual_support_session' ? 'selected' : '' }}>جلسات فردية</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">مدير المشروع</label>
                    <select name="manager_id" class="w-full">
                        <option value="">كل المدراء</option>
                        @foreach($managers as $m)
                            <option value="{{ $m->id }}" {{ request('manager_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">مدخل البيانات</label>
                    <select name="data_entry_id" class="w-full">
                        <option value="">كل مدخلي البيانات</option>
                        @foreach($dataEntry as $de)
                            <option value="{{ $de->id }}" {{ request('data_entry_id') == $de->id ? 'selected' : '' }}>{{ $de->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">من تاريخ</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 mr-1">إلى تاريخ</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit" class="px-8 bg-indigo-600 hover:bg-slate-900 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/10 font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    تحديث التقرير والبحث
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 bg-gray-50 hover:bg-gray-100 text-gray-500 flex items-center justify-center rounded-xl transition-all font-bold gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    إعادة ضبط
                </a>
            </div>
        </form>
    </div>


    {{-- ─── Stats Grid ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Users --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">المستخدمين</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['users_total'] }}</h3>
                    <span class="text-xs text-emerald-600 font-bold">{{ $stats['users_active'] }} نشط</span>
                </div>
            </div>
        </div>

        {{-- Total Projects --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">المشاريع</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['projects_total'] }}</h3>
                    <span class="text-xs text-indigo-600 font-bold">{{ $stats['projects_active'] }} جاري</span>
                </div>
            </div>
        </div>

        {{-- Total Beneficiaries --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">إجمالي المستفيدين</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_beneficiaries']) }}</h3>
            </div>
        </div>

        {{-- Pending Approvals --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">بانتظار الموافقة</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_approvals'] }}</h3>
            </div>
        </div>

    </div>

    {{-- ─── Charts Section ────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-6">
        
        {{-- Projects by Type (Bar Chart) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="text-base font-bold text-gray-800 mb-6">توزيع المشاريع حسب النوع</h4>
            <div class="h-64">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        {{-- Monthly Progress (Placeholder/Line Chart) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="text-base font-bold text-gray-800 mb-6">تطور الإنجاز الشهري</h4>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ─── Projects Results Table ──────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6 text-right">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h4 class="text-base font-bold text-gray-800">نتائج البحث في المشاريع والخدمات</h4>
            <span class="text-xs text-gray-500 font-bold">{{ $projects->count() }} مشروع موجود</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right" dir="rtl">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-right border-b">المشروع / الخدمة</th>
                        <th class="px-6 py-4 text-center border-b">نوع الخدمة</th>
                        <th class="px-6 py-4 text-center border-b">مدير المشروع</th>
                        <th class="px-6 py-4 text-center border-b">النطاق الزمني</th>
                        <th class="px-6 py-4 text-center border-b">الإنجاز (المعتمد)</th>
                        <th class="px-6 py-4 text-center border-b">الحالة</th>
                        <th class="px-6 py-4 text-left border-b">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $p->project_name ?? $p->name ?? $p->session_name ?? '—' }}
                                </p>
                                <p class="text-[11px] text-gray-400 font-bold mt-0.5">
                                    {{ $p->funder ?? $p->funding_agency ?? '—' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center border-r border-gray-50">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-bold">
                                    {{ $p->service_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600 font-bold border-r border-gray-50">
                                {{ $p->submitter?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-center text-[10px] text-gray-500 font-bold border-r border-gray-50">
                                {{ optional($p->start_date)->format('Y/m/d') }} - {{ optional($p->end_date)->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-center border-r border-gray-50">
                                <span class="text-sm font-bold text-emerald-600">{{ $p->approved_children_count }} تنفيذ</span>
                            </td>
                            <td class="px-6 py-4 text-center border-r border-gray-50">
                                @php
                                    $statusClasses = [
                                        'active' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        'completed' => 'text-blue-600 bg-blue-50 border-blue-100',
                                        'draft' => 'text-gray-400 bg-gray-50 border-gray-100',
                                    ][$p->status] ?? 'text-gray-400 bg-gray-50';
                                    $statusLabel = ['active'=>'نشط', 'completed'=>'مكتمل', 'draft'=>'مسودة'][$p->status] ?? $p->status;
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left border-r border-gray-50">
                                <a href="{{ route('admin.services.show', [$p->service_type_key, $p->id]) }}" 
                                   class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-900 font-bold text-xs transition-colors">
                                    عرض التفاصيل
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center text-gray-400 font-bold">لا يوجد مشاريع تطابق خيارات الفلترة المحددة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Chart.js Script --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Type Chart
        const ctxType = document.getElementById('typeChart').getContext('2d');
        new Chart(ctxType, {
            type: 'bar',
            data: {
                labels: @json($projectsByType->pluck('service_type')),
                datasets: [{
                    label: 'عدد المشاريع',
                    data: @json($projectsByType->pluck('count')),
                    backgroundColor: '#4F46E5',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Monthly Progress Chart (Dummy Data for now)
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'المستفيدين الجدد',
                    data: [120, 190, 300, 250, 400, 350],
                    borderColor: '#10B981',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endpush
@endsection
