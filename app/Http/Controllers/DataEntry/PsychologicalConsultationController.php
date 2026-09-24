<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\PsychologicalConsultationRequest;
use App\Models\PsychologicalConsultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PsychologicalConsultationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = PsychologicalConsultation::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $consultations = $query->paginate(10);
        return view('entry.services.psychological_consultation.index', compact('consultations'));
    }

    public function edit(PsychologicalConsultation $psychological_consultation): View
    {
        if ($psychological_consultation->parent_id !== null && $psychological_consultation->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($psychological_consultation->parent_id === null && $psychological_consultation->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        return view('entry.services.psychological_consultation.edit', compact('psychological_consultation'));
    }

    public function update(PsychologicalConsultationRequest $request, PsychologicalConsultation $psychological_consultation): RedirectResponse
    {
        if ($psychological_consultation->parent_id !== null) {
            if ($psychological_consultation->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $individual_data = \Illuminate\Support\Arr::except($request->validated(), ['attachments']);
            $individual_data['approval_status'] = 'pending';
            $individual_data['rejection_reason'] = null;
            $psychological_consultation->update($individual_data);
            
            $psychological_consultation->handleAttachments($request);
            
            return redirect()->route('data_entry.services.psychological_consultation.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }
        if ($psychological_consultation->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }
        $data = \Illuminate\Support\Arr::except($request->validated(), ['attachments']);
        $data['parent_id'] = $psychological_consultation->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        $data['project_name'] = $psychological_consultation->project_name;
        $data['funding_agency'] = $psychological_consultation->funding_agency;
        $data['start_date'] = $psychological_consultation->start_date;
        $data['end_date'] = $psychological_consultation->end_date;
        $data['sector_type'] = $psychological_consultation->sector_type;
        $data['project_description'] = $psychological_consultation->project_description;
        
        $child = PsychologicalConsultation::create($data);
        
        $child->handleAttachments($request);
        
        return redirect()->route('data_entry.services.psychological_consultation.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
