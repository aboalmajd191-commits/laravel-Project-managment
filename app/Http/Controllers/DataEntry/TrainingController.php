<?php

// app/Http/Controllers/DataEntry/TrainingController.php

declare(strict_types=1);

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\TrainingRequest;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $query = Training::with('submitter')->latest();

        if ($user->isDataEntry()) {
            // Show only ACTIVE parent trainings (Templates for entry)
            $query->whereNull('parent_id')->where('status', 'active');
            
            // Add custom check for previous submissions count by this user
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        } elseif ($user->isProjectManager()) {
            // ... (keep current behavior for Manager)
            $query->where(function($q) use ($user) {
                $q->where('submitted_by', $user->id)
                  ->orWhereHas('parent', function($pq) use ($user) {
                      $pq->where('submitted_by', $user->id);
                  });
            });
        }

        $trainings = $query->paginate(10);

        return view('entry.services.training.index', compact('trainings'));
    }

    public function edit(Training $training): View
    {
        // If it's a child (submission), don't allow edit if already approved
        if ($training->parent_id !== null && $training->approval_status === 'approved') {
            abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }
        
        // If it's a parent, ensure it is active
        if ($training->parent_id === null && $training->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }

        $training->load('attendees');

        return view('entry.services.training.edit', compact('training'));
    }

    public function update(TrainingRequest $request, Training $training): RedirectResponse
    {
        // Case 1: Editing an existing submission (Child)
        if ($training->parent_id !== null) {
            if ($training->approval_status === 'approved') {
                return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            // Update the existing child
            $data = $request->validated();
            \DB::transaction(function() use ($training, $data, $request) {
                $childData = \Illuminate\Support\Arr::except($data, ['attendees', 'attachments']);
                $childData['approval_status'] = 'pending';
                $childData['rejection_reason'] = null;
                $training->update($childData);
                if (isset($data['attendees'])) {
                    $training->attendees()->delete();
                    foreach ($data['attendees'] as $att) {
                        $training->attendees()->create($att);
                    }
                }
                $training->handleAttachments($request);
            });
            return redirect()->route('data_entry.services.training.index')->with('success', 'تم تحديث البيانات بنجاح.');
        }

        // Case 2: Creating a NEW submission from a Parent
        if ($training->status !== 'active') {
            return back()->with('error', 'هذا التدريب غير نشط حالياً.');
        }

        $data = $request->validated();
        
        $childData = \Illuminate\Support\Arr::except($data, ['attendees', 'status', 'attachments']);
        
        // Inherit essential fields from parent if not provided in the data entry request
        $childData['name']              = $data['name'] ?? $training->name;
        $childData['funder']            = $data['funder'] ?? $training->funder;
        $childData['beneficiary_count'] = $data['beneficiary_count'] ?? $training->beneficiary_count;
        $childData['start_date']        = $data['start_date'] ?? $training->start_date;
        $childData['end_date']          = $data['end_date'] ?? $training->end_date;
        
        $childData['parent_id']        = $training->id;
        $childData['approval_status']  = 'pending';
        $childData['rejection_reason'] = null;
        $childData['submitted_by']     = auth()->id();

        \DB::transaction(function() use ($data, $childData, $request) {
            $childTraining = Training::create($childData);
            
            if (isset($data['attendees'])) {
                foreach ($data['attendees'] as $attendeeData) {
                    $childTraining->attendees()->create($attendeeData);
                }
            }

            $childTraining->handleAttachments($request);
        });

        return redirect()->route('data_entry.services.training.index')
            ->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        // Only allow delete if not approved
        if ($training->approval_status === 'approved' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'لا يمكن حذف طلب تمت الموافقة عليه.');
        }

        $training->delete();

        return redirect()->route('data_entry.services.training.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
