<?php

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\AwarenessWorkshopRequest;
use App\Models\AwarenessWorkshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AwarenessWorkshopController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = AwarenessWorkshop::with('submitter')->latest();

        if ($user->isDataEntry()) {
            $query->whereNull('parent_id')->where('status', 'active');
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        }

        $workshops = $query->paginate(10);
        return view('entry.services.awareness_workshop.index', compact('workshops'));
    }

    public function edit(AwarenessWorkshop $awareness_workshop): View
    {
        // If it's a child (submission), don't allow edit if already approved
        if ($awareness_workshop->parent_id !== null && $awareness_workshop->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        
        // If it's a parent, ensure it is active
        if ($awareness_workshop->parent_id === null && $awareness_workshop->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }

        $awareness_workshop->load('attendees');

        return view('entry.services.awareness_workshop.edit', compact('awareness_workshop'));
    }

    public function update(AwarenessWorkshopRequest $request, AwarenessWorkshop $awareness_workshop): RedirectResponse
    {
        $validatedData = $request->validated();
        
        // Case 1: Editing an existing submission (Child)
        if ($awareness_workshop->parent_id !== null) {
            if ($awareness_workshop->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }

            DB::transaction(function() use ($awareness_workshop, $validatedData, $request) {
                $childData = Arr::except($validatedData, ['attendees', 'attachments']);
                $childData['approval_status'] = 'pending';
                $childData['rejection_reason'] = null;
                $awareness_workshop->update($childData);
                
                if (isset($validatedData['attendees'])) {
                    $awareness_workshop->attendees()->delete();
                    foreach ($validatedData['attendees'] as $att) {
                        if (!empty($att['name'])) {
                            $awareness_workshop->attendees()->create($att);
                        }
                    }
                }
                
                $awareness_workshop->handleAttachments($request);
            });

            return redirect()->route('data_entry.services.awareness_workshop.index')
                ->with('success', 'تم تحديث البيانات بنجاح.');
        }

        // Case 2: Creating a NEW submission from a Parent
        if ($awareness_workshop->status !== 'active') {
            return back()->with('error', 'هذه الورشة غير نشطة حالياً.');
        }

        $data = Arr::except($validatedData, ['attendees', 'attachments']);
        $data['parent_id'] = $awareness_workshop->id;
        $data['approval_status'] = 'pending';
        $data['submitted_by'] = auth()->id();
        
        // Inherit parent fields
        $data['project_name'] = $awareness_workshop->project_name;
        $data['funder'] = $awareness_workshop->funder;
        $data['start_date'] = $awareness_workshop->start_date;
        $data['end_date'] = $awareness_workshop->end_date;
        $data['total_beneficiaries'] = $awareness_workshop->total_beneficiaries;
        $data['project_description'] = $awareness_workshop->project_description;
        $data['sector_type'] = $awareness_workshop->sector_type;

        DB::transaction(function() use ($validatedData, $data, $request) {
            $newWorkshop = AwarenessWorkshop::create($data);

            if (isset($validatedData['attendees'])) {
                foreach ($validatedData['attendees'] as $att) {
                    if (!empty($att['name'])) {
                        $newWorkshop->attendees()->create($att);
                    }
                }
            }

            $newWorkshop->handleAttachments($request);
        });

        return redirect()->route('data_entry.services.awareness_workshop.index')
            ->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }
}
