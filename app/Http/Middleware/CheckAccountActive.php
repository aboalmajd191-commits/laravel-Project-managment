<?php

// app/Http/Middleware/CheckAccountActive.php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'تم تعطيل هذا الحساب. تواصل مع المدير.']);
        }

        return $next($request);
    }
}
