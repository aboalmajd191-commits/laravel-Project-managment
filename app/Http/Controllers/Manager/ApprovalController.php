<?php

// app/Http/Controllers/Manager/ApprovalController.php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ApprovalRejectRequest;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    protected ApprovalService $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    /**
     * عرض قائمة الطلبات المعلقة.
     */
    public function index(): View
    {
        $pendingApprovals = $this->approvalService->getPendingApprovals(auth()->user());

        return view('manager.approvals.index', compact('pendingApprovals'));
    }

    /**
     * عرض تفاصيل طلب محدد للمراجعة.
     */
    public function show(string $type, int $id): View
    {
        $record = $this->approvalService->getRecord($type, $id);
        
        $this->authorizeAction($record);

        // تحميل العلاقات بناءً على النوع
        if (in_array($type, ['training', 'awareness_workshop', 'group_support_session'])) {
            $record->load('attendees');
        } elseif ($type === 'economic_empowerment') {
            $record->load('images');
        } elseif ($type === 'individual_support_session') {
            $record->load('details');
        }

        return view('manager.approvals.show', [
            'record' => $record,
            'type'   => $type,
            'label'  => $this->approvalService->getServiceLabel($type)
        ]);
    }

    /**
     * الموافقة على طلب.
     */
    public function approve(Request $request, string $type, int $id): RedirectResponse
    {
        $record = $this->approvalService->getRecord($type, $id);
        
        $this->authorizeAction($record);

        $this->approvalService->approve($record, auth()->id());

        return back()->with('success', 'تمت الموافقة على الطلب بنجاح');
    }

    /**
     * رفض طلب مع ذكر السبب.
     */
    public function reject(ApprovalRejectRequest $request, string $type, int $id): RedirectResponse
    {
        $record = $this->approvalService->getRecord($type, $id);

        $this->authorizeAction($record);

        $this->approvalService->reject($record, $request->reason);

        return back()->with('success', 'تم رفض الطلب بنجاح');
    }

    /**
     * التحقق من صلاحية المستخدم للقيام بالإجراء.
     */
    private function authorizeAction($record): void
    {
        $user = auth()->user();
        
        if (!$user->isProjectManager() && !$user->isAdmin()) {
            abort(403);
        }

        // إضافي: التأكد أن المدير هو صاحب المشروع الأم (إلا إذا كان أدمن)
        if (!$user->isAdmin() && $record->parent && $record->parent->submitted_by !== $user->id) {
            abort(403, 'غير مصرح لك بالموافقة على هذا الطلب.');
        }
    }

}
