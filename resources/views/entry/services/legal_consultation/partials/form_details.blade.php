{{-- resources/views/entry/services/legal_consultation/partials/form_details.blade.php --}}
@php
    $item = $legal_consultation;
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم رباعي <span class="text-rose-500">*</span></label>
        <input type="text" name="full_name" value="{{ old('full_name', $item->full_name) }}" required
               class="w-full">
    </div>
      <div class="md:col-span-1">
        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم بالانجليزي</label>
        <input type="text" name="name_en" value="{{ old('name_en', $item->name_en) }}"
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الهوية <span class="text-rose-500">*</span></label>
        <input type="text" name="id_number" value="{{ old('id_number', $item->id_number) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الميلاد <span class="text-rose-500">*</span></label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $item->birth_date?->format('Y-m-d')) }}" required
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
        <label class="block text-sm font-bold text-gray-700 mb-2">الجنس <span class="text-rose-500">*</span></label>
        <select name="gender" required
                class="w-full">
            <option value="male" {{ old('gender', $item->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
            <option value="female" {{ old('gender', $item->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال <span class="text-rose-500">*</span></label>
        <input type="text" name="phone" value="{{ old('phone', $item->phone) }}" required
               class="w-full text-left" dir="ltr">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان الحالي <span class="text-rose-500">*</span></label>
        <input type="text" name="current_address" value="{{ old('current_address', $item->current_address) }}" required
               class="w-full">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان السابق <span class="text-rose-500">*</span></label>
        <input type="text" name="previous_address" value="{{ old('previous_address', $item->previous_address) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">عدد مرات النزوح <span class="text-rose-500">*</span></label>
        <input type="number" name="displacement_count" value="{{ old('displacement_count', $item->displacement_count) }}" required
               class="w-full">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">هل يوجد إعاقة؟</label>
        <select name="has_disability" class="w-full">
            <option value="0" {{ old('has_disability', $item->has_disability) == 0 ? 'selected' : '' }}>لا</option>
            <option value="1" {{ old('has_disability', $item->has_disability) == 1 ? 'selected' : '' }}>نعم</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">المصدر / الإحالة <span class="text-rose-500">*</span></label>
        <input type="text" name="source" value="{{ old('source', $item->source) }}" required
               class="w-full">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف المشكلة <span class="text-rose-500">*</span></label>
        <textarea name="problem_description" rows="3" required
                  class="w-full">{{ old('problem_description', $item->problem_description) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">تفاصيل الخدمة القانونية المقدمة <span class="text-rose-500">*</span></label>
        <textarea name="legal_aid_details" rows="3" required
                  class="w-full">{{ old('legal_aid_details', $item->legal_aid_details) }}</textarea>
    </div>
  
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">الوصف العام</label>
        <textarea name="description" rows="2"
                  class="w-full">{{ old('description', $item->description) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">المرفقات (صور، PDF، Excel)</label>
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
