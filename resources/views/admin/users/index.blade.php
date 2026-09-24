@extends('layouts.app')
{{-- resources/views/admin/users/index.blade.php --}}

@section('title', 'إدارة المستخدمين')
@section('page-title', 'كل المستخدمين')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">عرض وإدارة جميع حسابات الموظفين والمدراء في النظام</p>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إضافة مستخدم جديد
        </a>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-gray-600">
                    <tr>
                        <th class="px-6 py-4">المستخدم</th>
                        <th class="px-6 py-4">الدور</th>
                        <th class="px-6 py-4">الحالة</th>
                        <th class="px-6 py-4">تاريخ الانضمام</th>
                        <th class="px-6 py-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold uppercase">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColor = match($user->role) {
                                        'admin'           => 'bg-indigo-100 text-indigo-700',
                                        'project_manager' => 'bg-emerald-100 text-emerald-700',
                                        'data_entry'      => 'bg-amber-100 text-amber-700',
                                        default           => 'bg-gray-100 text-gray-600',
                                    };
                                    $roleLabel = match($user->role) {
                                        'admin'           => 'مدير النظام',
                                        'project_manager' => 'مدير المشروع',
                                        'data_entry'      => 'مدخل بيانات',
                                        default           => $user->role,
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $roleColor }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-xs text-gray-400 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        معطّل
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $user->created_at->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- Toggle Active --}}
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="{{ $user->is_active ? 'تعطيل الحساب' : 'تفعيل الحساب' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($user->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ ستنتقل البيانات للمحذوفات.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                                لا يوجد مستخدمين حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
