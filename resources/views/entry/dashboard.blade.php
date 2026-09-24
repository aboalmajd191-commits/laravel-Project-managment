@extends('layouts.app')
{{-- resources/views/entry/dashboard.blade.php --}}

@section('title', 'لوحة التحكم - مدخل البيانات')
@section('page-title', 'مرحباً، ' . auth()->user()->name)

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Right: My Projects --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Welcome Card --}}
            <div class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-600/20 flex flex-col justify-center min-h-[200px]">
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 -ml-32 -mt-32 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-2">طاب يومك، {{ auth()->user()->name }}</h3>
                    <p class="text-indigo-100 text-sm max-w-md leading-relaxed">
                        لديك صلاحية الوصول للمشاريع النشطة الموكلة إليك أدناه. 
                        يمكنك البدء بإضافة الأنشطة والتدريبات من خلال قائمة "خدماتي" في الجانب.
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 p-8 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.45l8.15 14.1H3.85L12 5.45zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/></svg>
                </div>
            </div>

            {{-- Assigned Projects List --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-800">مشاريعك النشطة</h4>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        // Fetch active services (Parents with status active)
                        $serviceModels = [
                            'training' => \App\Models\Training::class,
                            'economic_empowerment' => \App\Models\EconomicProject::class,
                            'awareness_workshop' => \App\Models\AwarenessWorkshop::class,
                            'legal_consultation' => \App\Models\LegalConsultation::class,
                            'psychological_consultation' => \App\Models\PsychologicalConsultation::class,
                            'judicial_representation' => \App\Models\JudicialRepresentation::class,
                            'legal_representation' => \App\Models\LegalRepresentation::class,
                            'mediation' => \App\Models\Mediation::class,
                            'individual_support_session' => \App\Models\IndividualSupportSession::class,
                            'group_support_session' => \App\Models\GroupSupportSession::class,
                        ];
                        
                        $activeServices = [];
                        foreach ($serviceModels as $key => $model) {
                            $items = $model::whereNull('parent_id')->where('status', 'active')->get();
                            foreach ($items as $item) {
                                $activeServices[] = [
                                    'id' => $item->id,
                                    'name' => $item->name ?? $item->project_name ?? $item->owner_name ?? 'خدمة غير معروفة',
                                    'type' => $key,
                                    'type_label' => match($key) {
                                        'training' => 'تدريب',
                                        'economic_empowerment' => 'تمكين اقتصادي',
                                        'awareness_workshop' => 'ورش توعوية',
                                        'legal_consultation' => 'استشارة قانونية',
                                        'psychological_consultation' => 'استشارة نفسية',
                                        'judicial_representation' => 'تمثيل قضائي',
                                        'legal_representation' => 'تمثيل قانوني',
                                        'mediation' => 'وساطة',
                                        'individual_support_session' => 'جلسات فردية',
                                        'group_support_session' => 'جلسات جماعية',
                                        default => $key
                                    }
                                ];
                            }
                        }
                    @endphp

                    @foreach($activeServices as $service)
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-indigo-200 transition-colors group">
                            <div class="flex items-start justify-between mb-4">
                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-lg">{{ $service['type_label'] }}</span>
                                <a href="{{ route('data_entry.services.' . $service['type'] . '.edit', $service['id']) }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </a>
                            </div>
                            <h5 class="text-sm font-bold text-gray-800 mb-1 font-arabic">{{ $service['name'] }}</h5>
                            <p class="text-[11px] text-gray-400 line-clamp-1">إضافة إدخال جديد للنشاط</p>
                        </div>
                    @endforeach

                    @if(empty($activeServices))
                        <div class="col-span-full bg-white py-12 border border-dashed border-gray-200 rounded-3xl text-center">
                            <p class="text-xs text-gray-400 font-bold italic">لا توجد خدمات نشطة بانتظار إدخال البيانات حالياً.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Left Sidebar: Recent Submissions --}}
        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-sm font-bold text-gray-800">آخر إدخالاتي</h4>
                    <a href="{{ route('data_entry.services.training.index') }}" class="text-[11px] text-indigo-600 hover:underline font-bold">الكل</a>
                </div>

                <div class="space-y-5">
                    @php
                        $recentSubmissions = collect();
                        foreach ($serviceModels as $key => $model) {
                            $items = $model::whereNotNull('parent_id')
                                ->where('submitted_by', auth()->id())
                                ->latest()
                                ->take(3)
                                ->get();
                            $recentSubmissions = $recentSubmissions->concat($items);
                        }
                        $allRecent = $recentSubmissions->sortByDesc('created_at')->take(5);
                    @endphp

                    @forelse($allRecent as $item)
                        <div class="flex gap-4 group">
                            <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center font-bold text-xs {{ $item->approval_status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($item->approval_status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                @if($item->approval_status === 'approved')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @elseif($item->approval_status === 'rejected')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 border-b border-gray-50 pb-4 group-last:border-0">
                                <h6 class="text-[13px] font-bold text-gray-800 truncate">
                                    {{ $item->name ?? $item->beneficiary_name ?? $item->owner_name ?? $item->project_name ?? 'إدخال مجهول' }}
                                </h6>
                                <p class="text-[10px] text-gray-400">{{ $item->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-6">لم تقم بأي إدخالات بعد</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-indigo-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h5 class="font-bold mb-2">تعبئة البيانات</h5>
                <p class="text-slate-500 text-[10px] leading-relaxed mb-6">قم باختيار النشاط من القائمة لتبدأ بإضافة المستفيدين.</p>
                <a href="{{ route('data_entry.services.training.index') }}" class="w-full bg-white text-slate-900 py-2.5 rounded-xl text-[13px] font-bold hover:bg-indigo-50 transition-colors">عرض السجلات</a>
            </div>

        </div>

    </div>

</div>
@endsection
