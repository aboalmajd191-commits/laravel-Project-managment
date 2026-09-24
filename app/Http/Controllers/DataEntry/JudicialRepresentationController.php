<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\JudicialRepresentationRequest;
use App\Models\JudicialRepresentation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JudicialRepresentationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = JudicialRepresentation::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $cases = $query->paginate(10);
        return view('entry.services.judicial_representation.index', compact('cases'));
    }

    public function edit(JudicialRepresentation $judicial_representation): View
    {
        if ($judicial_representation->parent_id !== null && $judicial_representation->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($judicial_representation->parent_id === null && $judicial_representation->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        return view('entry.services.judicial_representation.edit', compact('judicial_representation'));
    }

    public function update(JudicialRepresentationRequest $request, JudicialRepresentation $judicial_representation): RedirectResponse
    {
        $validatedData = $request->validated();
        $attachments = $request->file('attachments');
        unset($validatedData['attachments']);

        if ($judicial_representation->parent_id !== null) {
            if ($judicial_representation->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $validatedData['approval_status'] = 'pending';
            $validatedData['rejection_reason'] = null;
            $judicial_representation->update($validatedData);

            $judicial_representation->handleAttachments($request);

            return redirect()->route('data_entry.services.judicial_representation.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }
        if ($judicial_representation->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }
        $data = $validatedData;
        $data['parent_id'] = $judicial_representation->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        $data['project_name'] = $judicial_representation->project_name;
        $data['funding_agency'] = $judicial_representation->funding_agency;
        $data['start_date'] = $judicial_representation->start_date;
        $data['end_date'] = $judicial_representation->end_date;
        $data['sector_type'] = $judicial_representation->sector_type;
        $data['project_description'] = $judicial_representation->project_description;

        $newCase = JudicialRepresentation::create($data);

        $newCase->handleAttachments($request);

        return redirect()->route('data_entry.services.judicial_representation.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
