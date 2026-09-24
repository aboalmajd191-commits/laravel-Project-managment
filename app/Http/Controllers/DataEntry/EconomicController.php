<?php

// app/Http/Controllers/DataEntry/EconomicController.php

declare(strict_types=1);

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\EconomicRequest;
use App\Models\EconomicProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EconomicController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $query = EconomicProject::with('submitter')->latest();

        if ($user->isDataEntry()) {
            // Show only ACTIVE parent projects (Templates for entry)
            $query->whereNull('parent_id')->where('status', 'active');

            // Add count of existing submissions by THIS user for THIS parent
            $query->withCount(['children' => function($q) use ($user) {
                $q->where('submitted_by', $user->id);
            }]);
        } elseif ($user->isProjectManager()) {
            // Manager sees parents they created + all children of those parents
            $query->where(function($q) use ($user) {
                $q->where('submitted_by', $user->id)
                  ->orWhereHas('parent', function($pq) use ($user) {
                      $pq->where('submitted_by', $user->id);
                  });
            });
        }

        $economics = $query->paginate(10);

        return view('entry.services.economic.index', compact('economics'));
    }

    public function edit(EconomicProject $economic_empowerment): View
    {
        // If it's a child, don't allow edit if already approved
        if ($economic_empowerment->parent_id !== null && $economic_empowerment->approval_status === 'approved') {
             abort(403, 'لا يمكن تعديل طلب تمت الموافقة عليه.');
        }

        // If it's a parent, ensure it is active
        if ($economic_empowerment->parent_id === null && $economic_empowerment->status !== 'active') {
             abort(403, 'هذا النشاط غير متاح حالياً للإدخال.');
        }

        $economic_empowerment->load('images');

        return view('entry.services.economic.edit', ['economic' => $economic_empowerment]);
    }

    public function update(EconomicRequest $request, EconomicProject $economic_empowerment): RedirectResponse
    {
        // Case 1: Editing existing child
        if ($economic_empowerment->parent_id !== null) {
            if ($economic_empowerment->approval_status === 'approved') {
                 return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه.');
            }
            $data = $request->validated();
            \DB::transaction(function() use ($request, $economic_empowerment, $data) {
                $childData = \Illuminate\Support\Arr::except($data, ['images', 'attachments']);
                $childData['approval_status'] = 'pending';
                $childData['rejection_reason'] = null;
                $economic_empowerment->update($childData);
                
                // Legacy images
                if ($request->hasFile('images')) {
                    $economic_empowerment->images()->delete();
                    foreach ($request->file('images') as $image) {
                        $path = $image->store("economic-projects/{$economic_empowerment->id}", 'private');
                        $economic_empowerment->images()->create(['path' => $path]);
                    }
                }

                $economic_empowerment->handleAttachments($request);
            });
            return redirect()->route('data_entry.services.economic_empowerment.index')->with('success', 'تم التحديث بنجاح.');
        }

        // Case 2: Creating NEW from Parent
        if ($economic_empowerment->status !== 'active') {
            return back()->with('error', 'المشروع غير متاح للإدخال حالياً.');
        }

        $data = $request->validated();
        
        $childData = \Illuminate\Support\Arr::except($data, ['images', 'attachments', 'status']);

        // Inherit essential fields from parent if not in request
        $childData['project_name']      = $data['project_name'] ?? $economic_empowerment->project_name;
        $childData['coordinator_name']  = $data['coordinator_name'] ?? $economic_empowerment->coordinator_name;
        $childData['funder']            = $data['funder'] ?? $economic_empowerment->funder;
        $childData['total_grant_value'] = $data['total_grant_value'] ?? $economic_empowerment->total_grant_value;
        $childData['project_date']      = $data['project_date'] ?? $economic_empowerment->project_date;
        $childData['sector_type']       = $economic_empowerment->sector_type;
        $childData['project_description'] = $economic_empowerment->project_description;
        
        $childData['parent_id']        = $economic_empowerment->id;
        $childData['approval_status']  = 'pending';
        $childData['rejection_reason'] = null;
        $childData['submitted_by']     = auth()->id();

        \DB::transaction(function() use ($request, $childData) {
            $child = EconomicProject::create($childData);
            
            // Legacy images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store("economic-projects/{$child->id}", 'private');
                    $child->images()->create(['path' => $path]);
                }
            }

            $child->handleAttachments($request);
        });

        return redirect()->route('data_entry.services.economic_empowerment.index')
            ->with('success', 'تم حفظ البيانات وإرسالها للمراجعة.');
    }

    public function destroy(EconomicProject $economic_empowerment): RedirectResponse
    {
        if ($economic_empowerment->approval_status === 'approved' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'لا يمكن حذف طلب تمت الموافقة عليه.');
        }

        $economic_empowerment->delete();

        return redirect()->route('data_entry.services.economic_empowerment.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
