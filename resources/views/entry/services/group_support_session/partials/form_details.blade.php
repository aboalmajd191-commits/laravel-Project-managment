{{-- resources/views/entry/services/group_support_session/partials/form_details.blade.php --}}
@php
    $item = $group_support_session;
@endphp
<div class="space-y-8" x-data="attendanceHandler()">
    {{-- Session Meta Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-6 border-b border-gray-100">
        <div class="md:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">اسم اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="session_name" value="{{ old('session_name', $item->session_name) }}" required
                   class="w-full">
        </div>
        {{-- name_en removed from activity details --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">مكان اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="location" value="{{ old('location', $item->location) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">مدة اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="duration" value="{{ old('duration', $item->duration) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold font-bold">رقم الجلسة <span class="text-rose-500">*</span></label>
            <input type="number" name="session_number" value="{{ old('session_number', $item->session_number) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">عدد الحضور <span class="text-rose-500">*</span></label>
            <input type="number" name="attendees_count" value="{{ old('attendees_count', $item->attendees_count) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">مسير اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="session_leader" value="{{ old('session_leader', $item->session_leader) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">الجهة المستضيفة <span class="text-rose-500">*</span></label>
            <input type="text" name="hosting_entity" value="{{ old('hosting_entity', $item->hosting_entity) }}" required
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold font-bold">ميسر الجلسة <span class="text-rose-500">*</span></label>
            <input type="text" name="session_facilitator" value="{{ old('session_facilitator', $item->session_facilitator) }}" required
                   class="w-full">
        </div>
        <div class="lg:col-span-2">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف اللقاء / الجلسة</label>
            <textarea name="description" rows="2"
                      class="w-full">{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-sm font-bold text-gray-700 mb-2 font-bold">وصف المشروع</label>
            <textarea name="project_description" rows="2"
                      class="w-full">{{ old('project_description', $item->project_description) }}</textarea>
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Attendees Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-md font-bold text-gray-700 flex items-center gap-2 font-bold">
                قائمة المستفيدين / الحضور
            </h4>
            <div class="flex gap-3">
                <label class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    استيراد اكسل
                    <input type="file" class="hidden" accept=".xlsx, .xls, .csv" @change="importExcel($event)">
                </label>
                <button type="button" @click="addAttendee()" class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-xl font-bold hover:bg-emerald-100 transition-all flex items-center gap-2 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة حضور
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse min-w-[1000px]">
                <thead class="bg-gray-50/80 text-[12px] font-bold text-gray-500 border-b border-gray-100 font-bold">
                    <tr>
                        <th class="px-3 py-4">اسم المستفيد</th>
                        <th class="px-3 py-4">الاسم (EN)</th>
                        <th class="px-3 py-4">رقم الهوية</th>
                        <th class="px-3 py-4">الجوال</th>
                        <th class="px-3 py-4 w-28">الجنس</th>
                        <th class="px-3 py-4 w-20">تاريخ الميلاد</th>
                        <th class="px-3 py-4">المحافظة</th>
                        <th class="px-3 py-4">الحالة الاجتماعية</th>
                        <th class="px-3 py-4">الحالة الصحية</th>
                        <th class="px-3 py-4">نوع الإعاقة</th>
                        <th class="px-3 py-4 w-24">النزوح</th>
                        <th class="px-3 py-4 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="(att, index) in attendees" :key="index">
                        <tr class="hover:bg-gray-50/30 transition-all font-medium">
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][beneficiary_name]`" x-model="att.beneficiary_name" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][name_en]`" x-model="att.name_en"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][id_number]`" x-model="att.id_number" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][mobile]`" x-model="att.mobile" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <select :name="`attendees[${index}][gender]`" x-model="att.gender" required
                                        class="w-full">
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                            </td>
                            <td class="px-2 py-3">
                                <input type="date" :name="`attendees[${index}][birth_date]`" x-model="att.birth_date" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][governorate]`" x-model="att.governorate" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <select :name="`attendees[${index}][marital_status]`" x-model="att.marital_status" required
                                        class="w-full">
                                    <option value="">اختر</option>
                                    <option value="متزوج">متزوج</option>
                                    <option value="اعزب">اعزب</option>
                                    <option value="مطلق">مطلق</option>
                                    <option value="ارمل">ارمل</option>
                                </select>
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][health_status]`" x-model="att.health_status" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <select :name="`attendees[${index}][disability_type]`" x-model="att.disability_type"
                                        class="w-full">
                                    <option value="">لا يوجد</option>
                                    <option value="الإعاقة الحركية">الإعاقة الحركية</option>
                                    <option value="الإعاقة البصرية">الإعاقة البصرية</option>
                                    <option value="الإعاقة السمعية">الإعاقة السمعية</option>
                                    <option value="الإعاقة الذهنية/العقلية">الإعاقة الذهنية/العقلية</option>
                                    <option value="اضطرابات النمو العصبي">اضطرابات النمو العصبي</option>
                                    <option value="صعوبات التعلم المحددة">صعوبات التعلم المحددة</option>
                                    <option value="الإعاقة النفسية/الاجتماعية">الإعاقة النفسية/الاجتماعية</option>
                                    <option value="الإعاقات المتعددة">الإعاقات المتعددة</option>
                                </select>
                            </td>
                            <td class="px-2 py-3">
                                <input type="number" :name="`attendees[${index}][displacement_count]`" x-model="att.displacement_count" required
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3 text-center">
                                <button type="button" @click="removeAttendee(index)" x-show="attendees.length > 1" class="text-rose-400 hover:text-rose-600 font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
function attendanceHandler() {
    return {
        @php
            $attendeesData = $item->attendees->map(fn($a) => [
                'beneficiary_name' => $a->beneficiary_name,
                'name_en' => $a->name_en,
                'id_number' => $a->id_number,
                'mobile' => $a->mobile,
                'gender' => $a->gender,
                'birth_date' => $a->birth_date ? $a->birth_date->format('Y-m-d') : '',
                'governorate' => $a->governorate,
                'marital_status' => $a->marital_status,
                'health_status' => $a->health_status,
                'disability_type' => $a->disability_type ?? '',
                'displacement_count' => $a->displacement_count,
            ])->toArray();

            if (empty($attendeesData)) {
                $attendeesData = [[
                    'beneficiary_name' => '', 
                    'name_en' => '',
                    'id_number' => '', 
                    'mobile' => '', 
                    'gender' => 'female', 
                    'birth_date' => '', 
                    'governorate' => '', 
                    'marital_status' => '', 
                    'health_status' => '', 
                    'disability_type' => '',
                    'displacement_count' => 0
                ]];
            }
        @endphp
        attendees: @json($attendeesData),
        
        init() {
            if (this.attendees.length === 0) {
                this.addAttendee();
            }
        },

        addAttendee() {
            this.attendees.push({
                beneficiary_name: '',
                name_en: '',
                id_number: '',
                mobile: '',
                gender: 'female',
                birth_date: '',
                governorate: '',
                marital_status: '',
                health_status: '',
                disability_type: '',
                displacement_count: 0
            });
        },
        removeAttendee(index) {
            this.attendees.splice(index, 1);
        },

        importExcel(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet);

                const importedNodes = json.map(row => ({
                    beneficiary_name: row['الاسم'] || row['Name'] || row['اسم المستفيد'] || row['الاسم الكامل'] || '',
                    name_en: row['الاسم EN'] || row['Name EN'] || row['الاسم (EN)'] || '',
                    id_number: row['الهوية'] || row['ID'] || row['رقم الهوية'] || '',
                    mobile: row['الجوال'] || row['Phone'] || row['رقم الجوال'] || '',
                    gender: (row['الجنس'] || row['Gender'] || 'أنثى') === 'ذكر' ? 'male' : 'female',
                    birth_date: this.formatExcelDate(row['تاريخ الميلاد'] || row['Birth Date'] || row['تاريخ ميلاد'] || row['Birthday'] || ''),
                    governorate: row['المحافظة'] || row['Governorate'] || '',
                    marital_status: row['الحالة'] || row['Status'] || row['الحالة الاجتماعية'] || '',
                    health_status: row['الحالة الصحية'] || row['Health'] || '',
                    disability_type: row['نوع الإعاقة'] || row['Disability'] || row['الإعاقة'] || '',
                    displacement_count: row['النزوح'] || row['Displacement'] || 0
                }));

                if (importedNodes.length > 0) {
                    if (this.attendees.length === 1 && !this.attendees[0].beneficiary_name) {
                        this.attendees = importedNodes;
                    } else {
                        this.attendees = [...this.attendees, ...importedNodes];
                    }
                }
            };
            reader.readAsArrayBuffer(file);
        },

        formatExcelDate(val) {
            if (!val) return '';
            
            // Handle Excel Serial Number
            if (typeof val === 'number') {
                // Excel serial date adjustment (account for leap year bug in 1900 format)
                const date = new Date(Math.round((val - 25569) * 86400 * 1000));
                return date.toISOString().split('T')[0];
            }
            
            // Handle String formats (e.g. DD/MM/YYYY)
            if (typeof val === 'string') {
                let parts = val.split(/[.\/-]/);
                if (parts.length === 3) {
                    if (parts[0].length <= 2 && parts[2].length === 4) {
                        return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    }
                    if (parts[0].length === 4) {
                        return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
                    }
                }
                const parsed = new Date(val);
                if (!isNaN(parsed.getTime())) {
                    return parsed.toISOString().split('T')[0];
                }
            }

            return val;
        }
    }
}
</script>
