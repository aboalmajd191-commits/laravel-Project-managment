<?php

// app/Http/Controllers/DataEntry/BlankServiceController.php

declare(strict_types=1);

namespace App\Http\Controllers\DataEntry;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlankServiceController extends Controller
{
    /**
     * عارض الخدمة "قيد التطوير"
     */
    public function index(string $service_name): View
    {
        $labels = [
            'awareness_workshop'    => 'ورش توعوية',
            'cash_assistance'       => 'مساعدات نقدية',
            'in_kind_assistance'    => 'مساعدات عينية',
            'community_initiatives' => 'مبادرات مجتمعية',
            'support_sponsorship'   => 'دعم ورعاية أيتام',
        ];

        $title = $labels[$service_name] ?? 'خدمة جديدة';

        return view('entry.services.blank', compact('title'));
    }
}
