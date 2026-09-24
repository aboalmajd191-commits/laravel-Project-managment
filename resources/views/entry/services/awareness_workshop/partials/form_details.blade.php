{{-- resources/views/entry/services/awareness_workshop/partials/form_details.blade.php --}}
@php
    $item = $awareness_workshop;
@endphp
<div x-data="workshopForm()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">اسم اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="meeting_name" value="{{ old('meeting_name', $item->meeting_name) }}" required
                   class="w-full">
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">مكان اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="meeting_location" value="{{ old('meeting_location', $item->meeting_location) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">مدة اللقاء (بالدقائق/الساعات) <span class="text-rose-500">*</span></label>
            <input type="text" name="meeting_duration" value="{{ old('meeting_duration', $item->meeting_duration) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجلسة <span class="text-rose-500">*</span></label>
            <input type="text" name="session_number" value="{{ old('session_number', $item->session_number) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">عدد الحضور <span class="text-rose-500">*</span></label>
            <input type="number" name="attendance_count" value="{{ old('attendance_count', $item->attendance_count) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">مسير اللقاء <span class="text-rose-500">*</span></label>
            <input type="text" name="meeting_moderator" value="{{ old('meeting_moderator', $item->meeting_moderator) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">الجهة المستضيفة <span class="text-rose-500">*</span></label>
            <input type="text" name="hosting_party" value="{{ old('hosting_party', $item->hosting_party) }}" required
                   class="w-full">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">ميسر الجلسة <span class="text-rose-500">*</span></label>
            <input type="text" name="session_facilitator" value="{{ old('session_facilitator', $item->session_facilitator) }}" required
                   class="w-full">
        </div>
        
        <div class="md:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">وصف الورشة / اللقاء</label>
            <textarea name="description" rows="2"
                      class="w-full">{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-bold text-gray-700 mb-2">وصف المشروع</label>
            <textarea name="project_description" rows="2"
                      class="w-full">{{ old('project_description', $item->project_description) }}</textarea>
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

    {{-- Attendees Section --}}
    <div class="pt-8 border-t border-gray-100 space-y-6 mt-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 border-r-4 border-amber-500 pr-4">
                <h3 class="text-lg font-bold text-gray-800">قائمة الحضور / المستفيدين</h3>
            </div>
            <div class="flex gap-3">
                <label class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    استيراد اكسل
                    <input type="file" class="hidden" accept=".xlsx, .xls, .csv" @change="importExcel($event)">
                </label>
                <button type="button" @click="addAttendee()" 
                        class="bg-amber-50 hover:bg-amber-100 text-amber-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة مستفيد جديد
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm font-arabic">
                <thead class="bg-gray-50 text-gray-400 font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-right">الاسم الكامل</th>
                        <th class="px-4 py-3 text-right">الاسم (EN)</th>
                        <th class="px-4 py-3 text-right">رقم الهوية</th>
                        <th class="px-4 py-3 text-right">التخصص</th>
                        <th class="px-4 py-3 text-right">تاريخ الميلاد</th>
                        <th class="px-4 py-3 text-right">الجوال</th>
                        <th class="px-4 py-3 text-right">المحافظة</th>
                        <th class="px-4 py-3 text-right">الحالة</th>
                        <th class="px-4 py-3 text-center text-xs">نوع الإعاقة</th>
                        <th class="px-4 py-3 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 border-b border-gray-50">
                    <template x-for="(attendee, index) in attendees" :key="index">
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][name]`" x-model="attendee.name"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][name_en]`" x-model="attendee.name_en"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][id_number]`" x-model="attendee.id_number"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][specialty]`" x-model="attendee.specialty"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <input type="date" :name="`attendees[${index}][birth_date]`" x-model="attendee.birth_date"
                                       class="w-full text-center">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][phone]`" x-model="attendee.phone"
                                       class="w-full text-center">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" :name="`attendees[${index}][governorate]`" x-model="attendee.governorate"
                                       class="w-full">
                            </td>
                            <td class="px-2 py-3">
                                <select :name="`attendees[${index}][marital_status]`" x-model="attendee.marital_status"
                                        class="w-full text-center">
                                    <option value="">—</option>
                                    <option value="married">متزوج</option>
                                    <option value="single">اعزب</option>
                                    <option value="divorced">مطلق</option>
                                    <option value="widowed">ارمل</option>
                                </select>
                            </td>
                            <td class="px-2 py-3 text-center">
                                <select :name="`attendees[${index}][disability_type]`" x-model="attendee.disability_type"
                                        class="w-full text-center min-w-[120px]">
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
                                <button type="button" @click="removeAttendee(index)" x-show="attendees.length > 1"
                                        class="text-gray-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        function workshopForm() {
            @php
                $defaultAttendees = isset($item) && $item->attendees && $item->attendees->count() > 0 
                    ? $item->attendees->map(fn($a) => [
                        'name' => $a->name,
                        'name_en' => $a->name_en,
                        'id_number' => $a->id_number,
                        'specialty' => $a->specialty,
                        'birth_date' => $a->birth_date ? $a->birth_date->format('Y-m-d') : '',
                        'phone' => $a->phone,
                        'governorate' => $a->governorate,
                        'marital_status' => $a->marital_status,
                        'disability_type' => $a->disability_type ?? ''
                    ])->toArray() 
                    : [['name' => '', 'name_en' => '', 'id_number' => '', 'specialty' => '', 'birth_date' => '', 'phone' => '', 'governorate' => '', 'marital_status' => '', 'disability_type' => '']];
            @endphp
            return {
                attendees: @json(old('attendees', $defaultAttendees)),
                
                addAttendee() {
                    this.attendees.push({
                        name: '', name_en: '', id_number: '', specialty: '', birth_date: '', phone: '', governorate: '', marital_status: '', disability_type: ''
                    });
                },
                
                removeAttendee(index) {
                    if(this.attendees.length > 1) {
                        this.attendees.splice(index, 1);
                    }
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
                            name: row['الاسم'] || row['Name'] || row['اسم المستفيد'] || row['الاسم الكامل'] || '',
                            name_en: row['الاسم EN'] || row['Name EN'] || row['الاسم (EN)'] || '',
                            id_number: row['الهوية'] || row['ID'] || row['رقم الهوية'] || '',
                            specialty: row['التخصص'] || row['Specialty'] || '',
                            birth_date: this.formatExcelDate(row['تاريخ الميلاد'] || row['Birth Date'] || row['تاريخ ميلاد'] || row['Birthday'] || ''),
                            phone: row['الجوال'] || row['Phone'] || row['رقم الجوال'] || '',
                            governorate: row['المحافظة'] || row['Governorate'] || '',
                            marital_status: row['الحالة'] || row['Status'] || row['الحالة الاجتماعية'] || '',
                            disability_type: row['نوع الإعاقة'] || row['Disability'] || row['الإعاقة'] || ''
                        }));

                        if (importedNodes.length > 0) {
                            if (this.attendees.length === 1 && !this.attendees[0].name) {
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
                    if (typeof val === 'number') {
                        const date = new Date(Math.round((val - 25569) * 86400 * 1000));
                        return date.toISOString().split('T')[0];
                    }
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
    @endpush
</div>
