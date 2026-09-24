<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataEntry\TrainingRequest;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function index(): View
    {
        $trainings = Training::with('submitter')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return view('manager.services.training.index', compact('trainings'));
    }

    public function create(): View
    {
        return view('manager.services.training.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'parent_project'    => 'nullable|string|max:255',
            'beneficiary_count' => 'required|integer|min:1',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'funder'            => 'required|string|max:255',
            'status'            => 'required|in:draft,active,completed',
            'sector_type'       => 'required|in:protection,education,health',
        ]);

        $data['submitted_by'] = auth()->id();
        $data['approval_status'] = 'approved';
        $data['parent_id'] = null;

        Training::create($data);

        return redirect()->route('project_manager.services.training.index')
            ->with('success', 'تم إنشاء الخدمة بنجاح.');
    }

    public function show(Training $training): View
    {
        $training->load(['submitter', 'children.submitter', 'children.attendees']);
        return view('manager.services.training.show', compact('training'));
    }

    public function edit(Training $training): View
    {
        $training->load('attendees');
        return view('manager.services.training.edit', compact('training'));
    }

    public function update(Request $request, Training $training): RedirectResponse
    {
        // Manager can update shell fields or entry fields (using tab=entry)
        $data = $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'parent_project'    => 'nullable|string|max:255',
            'beneficiary_count' => 'sometimes|required|integer|min:1',
            'start_date'        => 'sometimes|required|date',
            'end_date'          => 'sometimes|required|date|after_or_equal:start_date',
            'funder'            => 'sometimes|required|string|max:255',
            'gender_type'       => 'sometimes|required|in:male,female,both',
            'status'            => 'sometimes|required|in:draft,active,completed',
            'sector_type'       => 'sometimes|required|in:protection,education,health',
            'attendees'         => 'sometimes|array',
            'attendees.*.name'  => 'nullable|string|max:255',
            'attendees.*.name_en' => 'nullable|string|max:255',
            'attendees.*.id_number' => 'nullable|string|max:20',
            'attendees.*.specialty' => 'nullable|string|max:255',
            'attendees.*.birth_date' => 'nullable|date',
            'attendees.*.phone' => 'nullable|string|max:20',
            'attendees.*.governorate' => 'nullable|string|max:100',
            'attendees.*.marital_status' => 'nullable|string|max:255',
            'attendees.*.disability_type' => 'nullable|string|max:255',
            'activity_name'     => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'project_description' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request, $training, $data) {
            if ($request->has('tab') && $request->tab == 'entry') {
                if (isset($data['attendees'])) {
                    $childData = Arr::except($data, ['attendees', 'status']);
                    $childData['name']              = $data['name'] ?? $training->name;
                    $childData['funder']            = $data['funder'] ?? $training->funder;
                    $childData['beneficiary_count'] = $data['beneficiary_count'] ?? $training->beneficiary_count;
                    $childData['start_date']        = $data['start_date'] ?? $training->start_date;
                    $childData['end_date']          = $data['end_date'] ?? $training->end_date;
                    $childData['sector_type']       = $data['sector_type'] ?? $training->sector_type;

                    $childData['parent_id'] = $training->id;
                    $childData['submitted_by'] = auth()->id();
                    $childData['approval_status'] = 'approved';
                    
                    $childTraining = Training::create($childData);
                    foreach ($data['attendees'] as $att) {
                        $childTraining->attendees()->create($att);
                    }
                }
            } else {
                $training->update(Arr::except($data, 'attendees'));
            }
        });

        return redirect()->route('project_manager.services.training.index')
            ->with('success', 'تم تحديث البيانات بنجاح.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        $training->delete();
        return redirect()->route('project_manager.services.training.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}

