{{-- resources/views/entry/services/individual_support_session/partials/form_details.blade.php --}}
@php
    $item = $individual_support_session;
@endphp
<div class="space-y-8" x-data="sessionHandler()">
    {{-- Personal Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6 border-b border-gray-50">
        <div class="lg:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">اسم المستفيد (بالعربي) <span class="text-rose-500">*</span></label>
            <input type="text" name="beneficiary_name" value="{{ old('beneficiary_name', $item->beneficiary_name) }}" required
                   class="w-full">
        </div>
        <div class="lg:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">الاسم (بالانجليزي)</label>
            <input type="text" name="name_en" value="{{ old('name_en', $item->name_en) }}"
                   class="w-full">
        </div>
        <div class="lg:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">الاخصائية المتابعة <span class="text-rose-500">*</span></label>
            <input type="text" name="specialist" value="{{ old('specialist', $item->specialist) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">كود الحالة <span class="text-rose-500">*</span></label>
            <input type="text" name="case_code" value="{{ old('case_code', $item->case_code) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">تاريخ الميلاد <span class="text-rose-500">*</span></label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $item->birth_date ? $item->birth_date->format('Y-m-d') : '') }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">رقم الجوال <span class="text-rose-500">*</span></label>
            <input type="text" name="mobile" value="{{ old('mobile', $item->mobile) }}" required
                   class="w-full">
        </div>
        <div class="lg:col-span-2">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">العنوان <span class="text-rose-500">*</span></label>
            <input type="text" name="address" value="{{ old('address', $item->address) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">المستوى التعليمي <span class="text-rose-500">*</span></label>
            <input type="text" name="education_level" value="{{ old('education_level', $item->education_level) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">الحالة الاجتماعية <span class="text-rose-500">*</span></label>
            <select name="marital_status" required
                    class="w-full">
                <option value="">اختر الحالة الاجتماعية</option>
                <option value="متزوج" {{ old('marital_status', $item->marital_status) == 'متزوج' ? 'selected' : '' }}>متزوج</option>
                <option value="اعزب" {{ old('marital_status', $item->marital_status) == 'اعزب' ? 'selected' : '' }}>اعزب</option>
                <option value="مطلق" {{ old('marital_status', $item->marital_status) == 'مطلق' ? 'selected' : '' }}>مطلق</option>
                <option value="ارمل" {{ old('marital_status', $item->marital_status) == 'ارمل' ? 'selected' : '' }}>ارمل</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">نوع الإعاقة</label>
            <select name="disability_type" class="w-full">
                <option value="">لا يوجد</option>
                <option value="الإعاقة الحركية" {{ old('disability_type', $item->disability_type) == 'الإعاقة الحركية' ? 'selected' : '' }}>الإعاقة الحركية</option>
                <option value="الإعاقة البصرية" {{ old('disability_type', $item->disability_type) == 'الإعاقة البصرية' ? 'selected' : '' }}>الإعاقة البصرية</option>
                <option value="الإعاقة السمعية" {{ old('disability_type', $item->disability_type) == 'الإعاقة السمعية' ? 'selected' : '' }}>الإعاقة السمعية</option>
                <option value="الإعاقة الذهنية/العقلية" {{ old('disability_type', $item->disability_type) == 'الإعاقة الذهنية/العقلية' ? 'selected' : '' }}>الإعاقة الذهنية/العقلية</option>
                <option value="اضطرابات النمو العصبي" {{ old('disability_type', $item->disability_type) == 'اضطرابات النمو العصبي' ? 'selected' : '' }}>اضطرابات النمو العصبي</option>
                <option value="صعوبات التعلم المحددة" {{ old('disability_type', $item->disability_type) == 'صعوبات التعلم المحددة' ? 'selected' : '' }}>صعوبات التعلم المحددة</option>
                <option value="الإعاقة النفسية/الاجتماعية" {{ old('disability_type', $item->disability_type) == 'الإعاقة النفسية/الاجتماعية' ? 'selected' : '' }}>الإعاقة النفسية/الاجتماعية</option>
                <option value="الإعاقات المتعددة" {{ old('disability_type', $item->disability_type) == 'الإعاقات المتعددة' ? 'selected' : '' }}>الإعاقات المتعددة</option>
            </select>
        </div>
        <div class="lg:col-span-4">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">الشكوى الرئيسية <span class="text-rose-500">*</span></label>
            <textarea name="main_complaint" rows="2" required
                      class="w-full">{{ old('main_complaint', $item->main_complaint) }}</textarea>
        </div>
        <div class="lg:col-span-4">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">الوصف</label>
            <textarea name="description" rows="2"
                      class="w-full">{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="lg:col-span-4">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">المرفقات (صور، PDF، Excel)</label>
            <input type="file" name="attachments[]" multiple
                   class="w-full">
            @if($item->attachments->count() > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($item->attachments as $attachment)
                        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-lg px-3 py-1 text-xs">
                            <span class="truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                            {{-- Add a link or delete button if needed --}}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Sessions Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-md font-bold text-gray-700 flex items-center gap-2">
                <span class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xs">02</span>
                تفاصيل الجلسات
            </h4>
            <button type="button" @click="addSession()" class="text-[12px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1.5 rounded-lg font-bold hover:bg-emerald-100 transition-all flex items-center gap-1 font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة جلسة أخرى
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(session, index) in sessions" :key="index">
                <div class="bg-gray-50/50 rounded-2xl border border-gray-200 p-5 relative group/session">
                    <button type="button" @click="removeSession(index)" x-show="sessions.length > 1" 
                            class="absolute -left-2 -top-2 w-7 h-7 bg-white text-rose-500 border border-rose-100 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover/session:opacity-100 transition-all font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 font-medium">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 font-bold">رقم الجلسة</label>
                            <input type="text" :name="`sessions[${index}][session_number]`" x-model="session.session_number" required
                                   class="w-full">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 font-bold">التاريخ</label>
                            <input type="date" :name="`sessions[${index}][session_date]`" x-model="session.session_date" required
                                   class="w-full">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 font-bold">الموعد</label>
                            <input type="text" :name="`sessions[${index}][session_time]`" x-model="session.session_time" required
                                   class="w-full">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-medium">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 font-bold">ملف التدخل الإرشادي (نصي)</label>
                            <textarea :name="`sessions[${index}][intervention_file]`" x-model="session.intervention_file" rows="2" required
                                      class="w-full"></textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 font-bold">ملخص التدخل الإرشادي</label>
                            <textarea :name="`sessions[${index}][intervention_summary]`" x-model="session.intervention_summary" rows="2" required
                                      class="w-full"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function sessionHandler() {
    return {
        @php
            $sessionsData = $item->details->map(fn($d) => [
                'session_number' => $d->session_number,
                'session_date' => $d->session_date?->format('Y-m-d'),
                'session_time' => $d->session_time,
                'intervention_file' => $d->intervention_file,
                'intervention_summary' => $d->intervention_summary,
            ])->toArray();
            
            if (empty($sessionsData)) {
                $sessionsData = [[
                    'session_number' => '1', 
                    'session_date' => '', 
                    'session_time' => '', 
                    'intervention_file' => '', 
                    'intervention_summary' => ''
                ]];
            }
        @endphp
        sessions: @json($sessionsData),
        
        init() {
            if (this.sessions.length === 0) {
                this.addSession();
            }
        },

        addSession() {
            this.sessions.push({
                session_number: (this.sessions.length + 1).toString(),
                session_date: '',
                session_time: '',
                intervention_file: '',
                intervention_summary: ''
            });
        },
        removeSession(index) {
            this.sessions.splice(index, 1);
        }
    }
}
</script>
