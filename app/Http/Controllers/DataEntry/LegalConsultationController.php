<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\LegalConsultationRequest;
use App\Models\LegalConsultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalConsultationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = LegalConsultation::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $consultations = $query->paginate(10);
        return view('entry.services.legal_consultation.index', compact('consultations'));
    }

    public function edit(LegalConsultation $legal_consultation): View
    {
        if ($legal_consultation->parent_id !== null && $legal_consultation->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($legal_consultation->parent_id === null && $legal_consultation->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        return view('entry.services.legal_consultation.edit', compact('legal_consultation'));
    }

    public function update(LegalConsultationRequest $request, LegalConsultation $legal_consultation): RedirectResponse
    {
        $validatedData = $request->validated();
        $attachments = $request->file('attachments');
        unset($validatedData['attachments']);

        if ($legal_consultation->parent_id !== null) {
            if ($legal_consultation->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $validatedData['approval_status'] = 'pending';
            $validatedData['rejection_reason'] = null;
            $legal_consultation->update($validatedData);

            $legal_consultation->handleAttachments($request);

            return redirect()->route('data_entry.services.legal_consultation.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }
        if ($legal_consultation->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }
        $data = $validatedData;
        $data['parent_id'] = $legal_consultation->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        $data['project_name'] = $legal_consultation->project_name;
        $data['funder'] = $legal_consultation->funder;
        $data['start_date'] = $legal_consultation->start_date;
        $data['end_date'] = $legal_consultation->end_date;
        $data['sector_type'] = $legal_consultation->sector_type;
        $data['project_description'] = $legal_consultation->project_description;
        
        $newConsultation = LegalConsultation::create($data);

        $newConsultation->handleAttachments($request);

        return redirect()->route('data_entry.services.legal_consultation.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
