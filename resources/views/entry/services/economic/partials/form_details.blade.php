{{-- resources/views/entry/services/economic/partials/form_details.blade.php --}}
<div class="space-y-8">
    <div class="flex items-center gap-2 border-r-4 border-indigo-500 pr-4">
        <h3 class="text-lg font-bold text-gray-800">بيانات المستفيد (صاحب المشروع)</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Owner Name --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">اسم المستفيد الكامل <span class="text-rose-500">*</span></label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $economic->owner_name ?? '') }}" required
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Name EN --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">الاسم (EN)</label>
            <input type="text" name="name_en" value="{{ old('name_en', $economic->name_en ?? '') }}"
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- ID Number --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">رقم الهوية <span class="text-rose-500">*</span></label>
            <input type="text" name="id_number" value="{{ old('id_number', $economic->id_number ?? '') }}" required
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال <span class="text-rose-500">*</span></label>
            <input type="text" name="phone" value="{{ old('phone', $economic->phone ?? '') }}" required
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Grant Value --}}
        @php
            $isChild = isset($economic) && $economic->parent_id !== null;
            $parentObj = $isChild ? $economic->parent : ($economic ?? null);
            $remainingBalance = 0;
            if ($parentObj) {
                $total = (float) $parentObj->total_grant_value;
                $used = $parentObj->children()
                    ->where('approval_status', 'approved')
                    ->when($isChild, function($q) use ($economic) {
                        $q->where('id', '!=', $economic->id);
                    })
                    ->sum('grant_value');
                $remainingBalance = max(0, $total - $used);
            }
        @endphp
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-bold text-gray-700">قيمة المنحة المستلمة <span class="text-rose-500">*</span></label>
                @if($parentObj)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                        المتبقي: {{ number_format($remainingBalance, 2) }}
                    </span>
                @endif
            </div>
            <input type="number" step="0.01" name="grant_value" value="{{ old('grant_value', $economic->grant_value ?? '') }}" required
                   max="{{ $remainingBalance }}"
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50"
                   placeholder="أدخل القيمة...">
            <p class="text-[10px] text-gray-400">يجب أن لا تتجاوز القيمة المدخلة الرصيد المتبقي.</p>
        </div>

        {{-- Family Count --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">عدد أفراد الأسرة <span class="text-rose-500">*</span></label>
            <input type="number" name="individuals_count" value="{{ old('individuals_count', $economic->individuals_count ?? '') }}" required
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Governorate --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">المحافظة <span class="text-rose-500">*</span></label>
            <input type="text" name="governorate" value="{{ old('governorate', $economic->governorate ?? '') }}" required
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Grant Date --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ المنحة</label>
            <input type="date" name="grant_date" value="{{ old('grant_date', isset($economic) && $economic->grant_date ? $economic->grant_date->format('Y-m-d') : '') }}"
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Marital Status --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">الحالة الاجتماعية</label>
            <select name="marital_status" class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
                <option value="">—</option>
                <option value="married" {{ old('marital_status', $economic->marital_status ?? '') == 'married' ? 'selected' : '' }}>متزوج</option>
                <option value="single" {{ old('marital_status', $economic->marital_status ?? '') == 'single' ? 'selected' : '' }}>اعزب</option>
                <option value="divorced" {{ old('marital_status', $economic->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>مطلق</option>
                <option value="widowed" {{ old('marital_status', $economic->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>ارمل</option>
            </select>
        </div>

        {{-- Education Level --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">المستوى التعليمي</label>
            <input type="text" name="education_level" value="{{ old('education_level', $economic->education_level ?? '') }}"
                   class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
        </div>

        {{-- Disability Type --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">نوع الإعاقة</label>
            <select name="disability_type"
                    class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">
                <option value="">لا يوجد</option>
                <option value="الإعاقة الحركية" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقة الحركية' ? 'selected' : '' }}>الإعاقة الحركية</option>
                <option value="الإعاقة البصرية" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقة البصرية' ? 'selected' : '' }}>الإعاقة البصرية</option>
                <option value="الإعاقة السمعية" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقة السمعية' ? 'selected' : '' }}>الإعاقة السمعية</option>
                <option value="الإعاقة الذهنية/العقلية" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقة الذهنية/العقلية' ? 'selected' : '' }}>الإعاقة الذهنية/العقلية</option>
                <option value="اضطرابات النمو العصبي" {{ old('disability_type', $economic->disability_type ?? '') === 'اضطرابات النمو العصبي' ? 'selected' : '' }}>اضطرابات النمو العصبي</option>
                <option value="صعوبات التعلم المحددة" {{ old('disability_type', $economic->disability_type ?? '') === 'صعوبات التعلم المحددة' ? 'selected' : '' }}>صعوبات التعلم المحددة</option>
                <option value="الإعاقة النفسية/الاجتماعية" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقة النفسية/الاجتماعية' ? 'selected' : '' }}>الإعاقة النفسية/الاجتماعية</option>
                <option value="الإعاقات المتعددة" {{ old('disability_type', $economic->disability_type ?? '') === 'الإعاقات المتعددة' ? 'selected' : '' }}>الإعاقات المتعددة</option>
            </select>
        </div>

        {{-- Project Description --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-gray-700 mb-2">وصف المشروع</label>
            <textarea name="project_description" rows="1"
                      class="w-full rounded-2xl border-gray-100 focus:border-indigo-500 text-sm py-3 bg-gray-50/50">{{ old('project_description', $economic->project_description ?? '') }}</textarea>
        </div>
    </div>

    {{-- Images & Attachments --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
            <label class="block text-sm font-bold text-gray-700">صور المشروع / التسليم (Legacy)</label>
            <input type="file" name="images[]" multiple class="w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            
            @if(isset($economic) && $economic->images->count() > 0)
                <div class="grid grid-cols-4 gap-2 mt-2">
                    @foreach($economic->images as $image)
                        <div class="relative group aspect-square rounded-lg overflow-hidden shadow-sm border border-gray-100">
                            <img src="{{ route('private.file', ['path' => $image->path]) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <label class="block text-sm font-bold text-gray-700">المرفقات العامة (صور، PDF، Excel)</label>
            <input type="file" name="attachments[]" multiple class="w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            
            @if(isset($economic) && $economic->attachments->count() > 0)
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($economic->attachments as $attachment)
                        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-lg px-3 py-1 text-xs">
                            <span class="truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
