<?php

// app/Http/Controllers/Admin/ReportController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Training;
use App\Models\EconomicProject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $projects = Project::orderBy('name')->get();
        return view('admin.reports.index', compact('projects'));
    }

    public function exportTrainings(Request $request): StreamedResponse
    {
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        $query = Training::with(['project', 'submitter'])->where('approval_status', 'approved');

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $trainings = $query->get();

        return new StreamedResponse(function() use ($trainings) {
            $handle = fopen('php://output', 'w');
            // Arabic CSV often needs UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'المشروع', 'العنوان', 'التاريخ', 'المكان', 'عدد المستفيدين', 'مدخل البيانات', 'الوصف'
            ]);

            foreach ($trainings as $t) {
                fputcsv($handle, [
                    $t->project->name,
                    $t->title,
                    $t->date->format('Y-m-d'),
                    $t->location,
                    $t->beneficiary_count,
                    $t->submitter->name,
                    $t->description,
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="trainings_report_'.date('Ymd').'.csv"',
        ]);
    }

    public function exportEconomics(Request $request): StreamedResponse
    {
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $query = EconomicProject::with(['project', 'submitter'])->where('approval_status', 'approved');

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $economics = $query->get();

        return new StreamedResponse(function() use ($economics) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'المشروع', 'صاحب المشروع', 'رقم الهوية', 'اسم المشروع الصغير', 'المحافظة', 'التكلفة', 'أفراد الأسرة', 'الهاتف', 'مدخل البيانات'
            ]);

            foreach ($economics as $e) {
                fputcsv($handle, [
                    $e->project->name,
                    $e->owner_name,
                    'ID: '.$e->owner_id_number,
                    $e->project_name,
                    $e->governorate,
                    $e->total_cost,
                    $e->individuals_count,
                    $e->phone,
                    $e->submitter->name,
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="economic_projects_'.date('Ymd').'.csv"',
        ]);
    }
}
