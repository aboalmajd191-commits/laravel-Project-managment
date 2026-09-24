<?php

declare(strict_types=1);

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\MediationRequest;
use App\Models\Mediation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = Mediation::with('submitter')->latest();
        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }
        $cases = $query->paginate(10);
        return view('entry.services.mediation.index', compact('cases'));
    }

    public function edit(Mediation $mediation): View
    {
        if ($mediation->parent_id !== null && $mediation->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        if ($mediation->parent_id === null && $mediation->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }
        return view('entry.services.mediation.edit', compact('mediation'));
    }

    public function update(MediationRequest $request, Mediation $mediation): RedirectResponse
    {
        $validatedData = $request->validated();
        $attachments = $request->file('attachments');
        unset($validatedData['attachments']);

        if ($mediation->parent_id !== null) {
            if ($mediation->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $validatedData['approval_status'] = 'pending';
            $validatedData['rejection_reason'] = null;
            $mediation->update($validatedData);

            $mediation->handleAttachments($request);

            return redirect()->route('data_entry.services.mediation.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }
        if ($mediation->status !== 'active') {
            return back()->with('error', 'هذه الخدمة غير نشطة حالياً.');
        }
        $data = $validatedData;
        $data['parent_id'] = $mediation->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        $data['project_name'] = $mediation->project_name;
        $data['funding_agency'] = $mediation->funding_agency;
        $data['start_date'] = $mediation->start_date;
        $data['end_date'] = $mediation->end_date;
        $data['sector_type'] = $mediation->sector_type;
        $data['project_description'] = $mediation->project_description;

        $newCase = Mediation::create($data);

        $newCase->handleAttachments($request);

        return redirect()->route('data_entry.services.mediation.index')->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
