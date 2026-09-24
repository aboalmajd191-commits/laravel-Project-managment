{{-- resources/views/entry/services/judicial_representation/partials/form_details.blade.php --}}
@php
    $item = $judicial_representation;
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-6 border-b border-gray-50 font-medium font-medium">
    <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المستفيد <span class="text-rose-500">*</span></label>
        <input type="text" name="beneficiary_name" value="{{ old('beneficiary_name', $item->beneficiary_name) }}" required
               class="w-full">
    </div>
    <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم (EN)</label>
        <input type="text" name="name_en" value="{{ old('name_en', $item->name_en) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">رقم الهوية <span class="text-rose-500">*</span></label>
        <input type="text" name="id_number" value="{{ old('id_number', $item->id_number) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">تاريخ الميلاد</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $item->birth_date?->format('Y-m-d')) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">المنطقة</label>
        <input type="text" name="region" value="{{ old('region', $item->region) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال</label>
        <input type="text" name="mobile" value="{{ old('mobile', $item->mobile) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">نوع الإعاقة</label>
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
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">الحالة الاجتماعية</label>
        <select name="marital_status"
                class="w-full">
            <option value="">اختر الحالة الاجتماعية</option>
            <option value="متزوج" {{ old('marital_status', $item->marital_status) == 'متزوج' ? 'selected' : '' }}>متزوج</option>
            <option value="اعزب" {{ old('marital_status', $item->marital_status) == 'اعزب' ? 'selected' : '' }}>اعزب</option>
            <option value="مطلق" {{ old('marital_status', $item->marital_status) == 'مطلق' ? 'selected' : '' }}>مطلق</option>
            <option value="ارمل" {{ old('marital_status', $item->marital_status) == 'ارمل' ? 'selected' : '' }}>ارمل</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">عدد أفراد الأسرة</label>
        <input type="number" name="individuals_count" value="{{ old('individuals_count', $item->individuals_count) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">الحالة الصحية</label>
        <input type="text" name="health_status" value="{{ old('health_status', $item->health_status) }}"
               class="w-full">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 font-medium">
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">عدد القضايا</label>
        <input type="number" name="cases_count" value="{{ old('cases_count', $item->cases_count) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">نوع القضية</label>
        <input type="text" name="case_type" value="{{ old('case_type', $item->case_type) }}"
               class="w-full" placeholder="شرعية / نظامية">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم القضية</label>
        <input type="text" name="case_number" value="{{ old('case_number', $item->case_number) }}"
               class="w-full">
    </div>
    <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">اسم / نوع الدعوى</label>
        <input type="text" name="case_name_type" value="{{ old('case_name_type', $item->case_name_type) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">المحكمة</label>
        <input type="text" name="court_name" value="{{ old('court_name', $item->court_name) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ رفع الدعوى</label>
        <input type="date" name="lawsuit_date" value="{{ old('lawsuit_date', $item->lawsuit_date?->format('Y-m-d')) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">حالة القضية</label>
        <select name="case_status"
                class="w-full">
            <option value="">اختر الحالة</option>
            <option value="متداولة" {{ old('case_status', $item->case_status) == 'متداولة' ? 'selected' : '' }}>متداولة</option>
            <option value="مغلقة" {{ old('case_status', $item->case_status) == 'مغلقة' ? 'selected' : '' }}>مغلقة</option>
        </select>
    </div>
    <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الإغلاق (إن وجد)</label>
        <input type="date" name="closing_date" value="{{ old('closing_date', $item->closing_date?->format('Y-m-d')) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">إجمالي المبالغ المحصلة</label>
        <input type="number" step="0.01" name="total_paid" value="{{ old('total_paid', $item->total_paid) }}"
               class="w-full">
    </div>
    <div class="lg:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف النشاط</label>
        <textarea name="description" rows="2"
                  class="w-full">{{ old('description', $item->description) }}</textarea>
    </div>
    <div class="lg:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">وصف المشروع</label>
        <textarea name="project_description" rows="2"
                  class="w-full">{{ old('project_description', $item->project_description) }}</textarea>
    </div>
    <div class="lg:col-span-3">
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
