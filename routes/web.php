<?php

// routes/web.php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Rate limiting for login ──────────────────────────────────────────────────
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

// ─── Root redirect ────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return match (auth()->user()->role) {
        'admin'           => redirect()->route('admin.dashboard'),
        'project_manager' => redirect()->route('project_manager.dashboard'),
        'data_entry'      => redirect()->route('data_entry.dashboard'),
        default           => redirect()->route('login'),
    };
});

// ─── Auth routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── File serving ─────────────────────────────────────────────────────────────
Route::middleware('auth')->get('/private-file', [\App\Http\Controllers\FileController::class, 'serve'])->name('private.file');

// ─── Role-based dashboard route files ────────────────────────────────────────
require __DIR__.'/admin.php';
require __DIR__.'/manager.php';
require __DIR__.'/data-entry.php';
