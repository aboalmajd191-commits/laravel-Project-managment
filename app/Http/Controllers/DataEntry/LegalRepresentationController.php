<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\LegalRepresentationRequest;
use App\Models\LegalRepresentation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalRepresentationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = LegalRepresentation::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $cases = $query->paginate(10);
        return view('entry.services.legal_representation.index', compact('cases'));
    }

    public function edit(LegalRepresentation $legal_representation): View
    {
        if ($legal_representation->parent_id !== null && $legal_representation->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($legal_representation->parent_id === null && $legal_representation->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        return view('entry.services.legal_representation.edit', compact('legal_representation'));
    }

    public function update(LegalRepresentationRequest $request, LegalRepresentation $legal_representation): RedirectResponse
    {
        $validatedData = $request->validated();
        $attachments = $request->file('attachments');
        unset($validatedData['attachments']);

        if ($legal_representation->parent_id !== null) {
            if ($legal_representation->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $validatedData['approval_status'] = 'pending';
            $validatedData['rejection_reason'] = null;
            $legal_representation->update($validatedData);

            $legal_representation->handleAttachments($request);

            return redirect()->route('data_entry.services.legal_representation.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }
        if ($legal_representation->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }
        $data = $validatedData;
        $data['parent_id'] = $legal_representation->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        $data['project_name'] = $legal_representation->project_name;
        $data['funding_agency'] = $legal_representation->funding_agency;
        $data['start_date'] = $legal_representation->start_date;
        $data['end_date'] = $legal_representation->end_date;
        $data['sector_type'] = $legal_representation->sector_type;
        $data['project_description'] = $legal_representation->project_description;

        $newCase = LegalRepresentation::create($data);

        $newCase->handleAttachments($request);

        return redirect()->route('data_entry.services.legal_representation.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
