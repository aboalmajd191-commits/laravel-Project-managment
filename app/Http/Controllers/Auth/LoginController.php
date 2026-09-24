<?php

// app/Http/Controllers/Auth/LoginController.php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check if account is disabled before attempting login
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'تم تعطيل هذا الحساب. تواصل مع المدير.',
            ])->onlyInput('email');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectBasedOnRole(): RedirectResponse
    {
        return match (Auth::user()->role) {
            'admin'           => redirect()->route('admin.dashboard'),
            'project_manager' => redirect()->route('project_manager.dashboard'),
            'data_entry'      => redirect()->route('data_entry.dashboard'),
            default           => redirect()->route('login'),
        };
    }
}
