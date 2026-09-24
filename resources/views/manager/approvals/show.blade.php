@extends('layouts.app')

@section('title', 'مراجعة طلب - ' . $label)
@section('page-title', 'مراجعة طلب إدخال بيانات')

@section('content')
<div x-data="{ 
    showRejectModal: false,
    rejectReason: ''
}" class="space-y-6 pb-12 text-right font-medium" dir="rtl">

    {{-- ─── HEADER & ACTIONS ────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-amber-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $record->full_name ?? $record->beneficiary_name ?? $record->owner_name ?? $record->project_name ?? $record->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100 uppercase">{{ $label }}</span>
                    <span class="text-[11px] text-gray-400 font-bold">بواسطة: {{ $record->submitter?->name }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('project_manager.approvals.approve', ['type' => $type, 'id' => $record->id]) }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-2.5 rounded-2xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    موافقة واعتماد
                </button>
            </form>
            <button @click="showRejectModal = true" class="px-8 py-2.5 rounded-2xl bg-white border border-rose-100 text-rose-600 text-xs font-bold hover:bg-rose-50 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                رفض الطلب
            </button>
            <a href="{{ route('project_manager.approvals.index') }}" class="px-6 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition-all">إلغاء</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ─── LEFT: DATA DETAILS ───────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-r-4 border-indigo-500 pr-4">تفاصيل البيانات المُدخلة</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    @php
                        $exclude = ['id', 'project_id', 'parent_id', 'submitted_by', 'approved_by', 'approved_at', 'approval_status', 'status', 'created_at', 'updated_at', 'deleted_at', 'rejection_reason'];
                        $attributes = collect($record->getAttributes())->except($exclude);
                    @endphp

                    @foreach($attributes as $key => $value)
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('fields.' . $key) != 'fields.' . $key ? __('fields.' . $key) : str_replace('_', ' ', $key) }}</span>
                            <span class="text-sm font-bold text-slate-700">
                                @if($value instanceof \DateTimeInterface)
                                    {{ $value->format('Y/m/d') }}
                                @elseif(is_numeric($value) && str_contains($key, 'cost'))
                                    {{ number_format((float)$value, 2) }} ₪
                                @else
                                    {{ $value ?: '—' }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($record->description || $record->project_description)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-widest mb-3">وصف إضافي</h4>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $record->description ?? $record->project_description }}</p>
            </div>
            @endif

            {{-- Attendees Section --}}
            @if(isset($record->attendees) && $record->attendees->count() > 0)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">قائمة المستفيدين / الحضور</h3>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-bold">{{ $record->attendees->count() }} شخص</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right">
                        <thead class="bg-slate-50 text-gray-500 font-bold">
                            <tr>
                                <th class="px-6 py-4">الاسم</th>
                                <th class="px-6 py-4">رقم الهوية</th>
                                <th class="px-6 py-4">الجوال</th>
                                <th class="px-6 py-4">الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($record->attendees as $att)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $att->full_name ?? $att->beneficiary_name ?? $att->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $att->id_number }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $att->phone ?? $att->mobile }}</td>
                                <td class="px-6 py-4 text-gray-400 text-xs italic">{{ $att->notes ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- ─── RIGHT: SIDEBAR (FILES & META) ────────────────────────── --}}
        <div class="space-y-6">
            
            {{-- Files / Attachments --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    المرفقات والمستندات
                </h3>

                @php
                    $files = method_exists($record, 'attachments') ? $record->attachments : collect();
                    if($type === 'economic_empowerment' && isset($record->images)) {
                        $files = $files->concat($record->images);
                    }
                @endphp

                @if($files->count() > 0)
                <div class="grid grid-cols-1 gap-3">
                    @foreach($files as $file)
                    <div class="group flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-indigo-200 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-500 shadow-sm">
                                @php $ext = pathinfo($file->file_path ?? $file->image_path, PATHINFO_EXTENSION); @endphp
                                @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-700 truncate w-32">{{ $file->label ?? $file->type ?? basename($file->file_path ?? $file->image_path) }}</p>
                                <p class="text-[10px] text-gray-400 font-bold">{{ strtoupper($ext) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('private.file', ['path' => $file->file_path ?? $file->image_path]) }}" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-xs text-gray-400 italic">لا يوجد مرفقات لهذا الطلب</p>
                </div>
                @endif
            </div>

            {{-- Meta info --}}
            <div class="bg-slate-900 rounded-3xl p-8 text-white">
                <h4 class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-6">معلومات السجل</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs opacity-70">تم الإدخال في:</span>
                        <span class="text-xs font-bold">{{ $record->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs opacity-70">بواسطة:</span>
                        <span class="text-xs font-bold">{{ $record->submitter->name }}</span>
                    </div>
                    @if($record->parent)
                    <div class="pt-4 border-t border-white/10">
                        <span class="text-[10px] opacity-50 block mb-1">المشروع الأب:</span>
                        <span class="text-xs font-bold text-indigo-300">{{ $record->parent->project_name ?? $record->parent->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div x-show="showRejectModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden font-medium" @click.away="showRejectModal = false">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-2">رفض الطلب</h3>
                <p class="text-sm text-gray-500 mb-6 font-bold">يرجى كتابة سبب الرفض ليتمكن مدخل البيانات من تصحيحه.</p>

                <form action="{{ route('project_manager.approvals.reject', ['type' => $type, 'id' => $record->id]) }}" method="POST">
                    @csrf
                    <textarea name="reason" rows="4" required placeholder="مثلاً: البيانات الشخصية غير كاملة..."
                              class="w-full"></textarea>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-rose-600/20 transition-all">تأكيد الرفض</button>
                        <button type="button" @click="showRejectModal = false" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold py-3 rounded-2xl transition-all font-bold">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
