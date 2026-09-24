{{-- resources/views/components/sidebar.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role;
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    
    $roleLabel = match($role) {
        'admin'           => 'مدير النظام',
        'project_manager' => 'مدير المشروع',
        'data_entry'      => 'مدخل البيانات',
        default           => $role,
    };
@endphp

<aside class="w-72 bg-[#1E293B] text-slate-300 flex flex-col fixed right-0 inset-y-0 z-30 shadow-2xl overflow-hidden border-l border-white/5">
    
    {{-- ─── SIDEBAR HEADER ────────────────────────────────────── --}}
    <div class="h-16 flex items-center px-6 bg-slate-900/50 border-b border-white/5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-600/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-white tracking-wide">  إدارة مشاريع المؤسسة</span>
        </div>
    </div>

    {{-- ─── NAVIGATION ────────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto py-6 px-4 sidebar-scroll space-y-1">
        
        {{-- Dashboard (All) --}}
        <x-sidebar-link :href="route($role . '.dashboard')" :active="str_contains($currentRoute, '.dashboard')" icon="home">
            الرئيسية
        </x-sidebar-link>

        {{-- Projects (Manager only) --}}
        @if($role === 'project_manager')
            <x-sidebar-link :href="route('project_manager.projects.index')" :active="str_contains($currentRoute, 'projects.')" icon="folder">
                المشاريع
            </x-sidebar-link>
        @endif

        {{-- My Submissions (Data Entry Only) --}}
        @if($role === 'data_entry')
            <x-sidebar-link :href="route('data_entry.submissions.index')" :active="str_contains($currentRoute, 'submissions.')" icon="folder-open">
                طلباتي المقدمة
            </x-sidebar-link>
        @endif

        {{-- Users Management (Admin only) --}}
        @if($role === 'admin')
            <x-sidebar-link :href="Route::has('admin.users.index') ? route('admin.users.index') : '#'" :active="str_contains($currentRoute, 'users.')" icon="users">
                إدارة المستخدمين
            </x-sidebar-link>
        @endif


        {{-- Pending Approvals (Manager only) --}}
        @if($role === 'project_manager')
            <x-sidebar-link :href="Route::has('project_manager.approvals.index') ? route('project_manager.approvals.index') : '#'" :active="str_contains($currentRoute, 'approvals.')" icon="check-square">
                طلبات الموافقة
                <span class="mr-auto bg-amber-500/20 text-amber-500 text-[10px] px-1.5 py-0.5 rounded-md border border-amber-500/20">جديد</span>
            </x-sidebar-link>
        @endif

        {{-- ─── SERVICES DROPDOWN (Manager, Data Entry) ─────────── --}}
        @if(in_array($role, ['project_manager', 'data_entry']))
            <div x-data="{ open: {{ str_contains($currentRoute, '.services.') ? 'true' : 'false' }} }" class="space-y-1 pt-4">
                <button @click="open = !open" 
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group hover:bg-white/5 ripple-effect"
                        :class="open ? 'text-white font-semibold' : 'text-slate-400 font-medium'">
                    <svg class="w-5 h-5 transition-colors" :class="open ? 'text-indigo-500' : 'text-slate-500 group-hover:text-slate-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>خدماتي</span>
                    <svg class="w-4 h-4 mr-auto transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="mr-4 pr-4 border-r border-white/5 space-y-0.5 overflow-hidden">
                    
                    @php
                        $sections = [
                            ['title' => 'خدمات عامة', 'services' => [
                                ['training', 'تدريب وبناء قدرات'],
                                ['awareness_workshop', 'ورش توعوية'],
                                ['economic_empowerment', 'التمكين الاقتصادي'],
                            ]],
                            ['title' => 'الاستشارات', 'services' => [
                                ['legal_consultation', 'استشارة قانونية'],
                                ['psychological_consultation', 'استشارة نفسية'],
                            ]],
                            ['title' => 'القضايا', 'services' => [
                                ['judicial_representation', 'تمثيل قضائي'],
                                ['legal_representation', 'تمثيل قانوني'],
                                ['mediation', 'وساطة'],
                            ]],
                            ['title' => 'الدعم النفسي', 'services' => [
                                ['individual_support_session', 'جلسات فردية'],
                                ['group_support_session', 'جلسات جماعية'],
                            ]],
                        ];
                        $prefix = $role;
                    @endphp

                    @foreach($sections as $section)
                        <div class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest border-t border-white/5 mt-2 first:mt-0 first:border-t-0">
                            {{ $section['title'] }}
                        </div>
                        @foreach($section['services'] as $service)
                            <a href="{{ Route::has($prefix.'.services.'.$service[0].'.index') ? route($prefix.'.services.'.$service[0].'.index') : '#' }}" 
                               class="block px-3 py-2 rounded-lg text-[13px] transition-all duration-150 {{ str_contains($currentRoute, '.'.$service[0].'.') ? 'text-indigo-400 font-bold bg-indigo-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                {{ $service[1] }}
                            </a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif

    </nav>

    {{-- ─── SIDEBAR FOOTER ────────────────────────────────────── --}}
    <div class="p-4 bg-slate-900/50 border-t border-white/5">
        <div class="flex items-center gap-3 px-3 py-2 bg-white/5 rounded-2xl border border-white/5">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-inner shadow-white/10 uppercase">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                    {{ $roleLabel }}
                </p>
            </div>
        </div>
    </div>
</aside>

