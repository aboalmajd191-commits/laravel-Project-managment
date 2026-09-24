<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProjectStatusController extends Controller
{
    /**
     * تحديث حالة المشروع بسرعة.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,active,completed',
            'type'   => 'sometimes|string'
        ]);

        $type = $request->input('type', 'project_shell');
        
        $serviceModels = [
            'project_shell' => \App\Models\Project::class,
            'training' => \App\Models\Training::class,
            'economic_empowerment' => \App\Models\EconomicProject::class,
            'awareness_workshop' => \App\Models\AwarenessWorkshop::class,
            'legal_consultation' => \App\Models\LegalConsultation::class,
            'psychological_consultation' => \App\Models\PsychologicalConsultation::class,
            'judicial_representation' => \App\Models\JudicialRepresentation::class,
            'legal_representation' => \App\Models\LegalRepresentation::class,
            'mediation' => \App\Models\Mediation::class,
            'individual_support_session' => \App\Models\IndividualSupportSession::class,
            'group_support_session' => \App\Models\GroupSupportSession::class,
        ];

        $modelClass = $serviceModels[$type] ?? \App\Models\Project::class;
        $item = $modelClass::findOrFail($id);

        // Security check
        if ($type === 'project_shell') {
            if ($item->manager_id !== auth()->id()) abort(403);
        } else {
            if ($item->submitted_by !== auth()->id()) abort(403);
        }

        $item->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة المشروع بنجاح.');
    }
}
