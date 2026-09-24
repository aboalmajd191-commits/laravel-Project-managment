{{-- resources/views/entry/services/psychological_consultation/partials/form_details.blade.php --}}
@php
    $item = $psychological_consultation;
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6 border-b border-gray-50 font-medium">
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم رباعي <span class="text-rose-500">*</span></label>
        <input type="text" name="full_name" value="{{ old('full_name', $item->full_name) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold uppercase tracking-wider">الاسم (بالانجليزي)</label>
        <input type="text" name="name_en" value="{{ old('name_en', $item->name_en) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">كود الحالة <span class="text-rose-500">*</span></label>
        <input type="text" name="case_code" value="{{ old('case_code', $item->case_code) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الهوية <span class="text-rose-500">*</span></label>
        <input type="text" name="id_number" value="{{ old('id_number', $item->id_number) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الميلاد <span class="text-rose-500">*</span></label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $item->birth_date ? $item->birth_date->format('Y-m-d') : '') }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال <span class="text-rose-500">*</span></label>
        <input type="text" name="mobile" value="{{ old('mobile', $item->mobile) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">الحالة الاجتماعية <span class="text-rose-500">*</span></label>
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
        <label class="block text-sm font-bold text-gray-700 mb-2">المستوى التعليمي <span class="text-rose-500">*</span></label>
        <input type="text" name="education_level" value="{{ old('education_level', $item->education_level) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">المهمة <span class="text-rose-500">*</span></label>
        <input type="text" name="mission" value="{{ old('mission', $item->mission) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">حالة النزوح <span class="text-rose-500">*</span></label>
        <input type="text" name="displacement_status" value="{{ old('displacement_status', $item->displacement_status) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">المحافظة الأصلية <span class="text-rose-500">*</span></label>
        <input type="text" name="original_governorate" value="{{ old('original_governorate', $item->original_governorate) }}" required
               class="w-full">
    </div>
    <div class="lg:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان الدائم <span class="text-rose-500">*</span></label>
        <input type="text" name="primary_address" value="{{ old('primary_address', $item->primary_address) }}" required
               class="w-full">
    </div>
    <div class="lg:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">عنوان النزوح <span class="text-rose-500">*</span></label>
        <input type="text" name="displacement_address" value="{{ old('displacement_address', $item->displacement_address) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">الحالة الصحية <span class="text-rose-500">*</span></label>
        <input type="text" name="health_status" value="{{ old('health_status', $item->health_status) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">نوع الإعاقة</label>
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
</div>

<div class="space-y-6 pt-6">
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف الحالة <span class="text-rose-500">*</span></label>
        <textarea name="case_description" rows="3" required
                  class="w-full">{{ old('case_description', $item->case_description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">الإجراء والتوجيه <span class="text-rose-500">*</span></label>
        <textarea name="procedure_guidance" rows="3" required
                  class="w-full">{{ old('procedure_guidance', $item->procedure_guidance) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">التوصيات <span class="text-rose-500">*</span></label>
        <textarea name="recommendations" rows="2" required
                  class="w-full">{{ old('recommendations', $item->recommendations) }}</textarea>
    </div>
    <div class="lg:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف النشاط</label>
        <textarea name="description" rows="2"
                  class="w-full">{{ old('description', $item->description) }}</textarea>
    </div>
    <div class="lg:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">وصف المشروع</label>
        <textarea name="project_description" rows="2"
                  class="w-full">{{ old('project_description', $item->project_description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">المرفقات (صور، PDF، Excel)</label>
        <input type="file" name="attachments[]" multiple
               class="w-full">
        @if($item->attachments->count() > 0)
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($item->attachments as $attachment)
                    <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-lg px-3 py-1 text-xs">
                        <span class="truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
