<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'account.active'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/services/{type}/{id}', [DashboardController::class, 'showService'])->name('services.show');
        
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Reporting
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/trainings', [ReportController::class, 'exportTrainings'])->name('reports.trainings');
        Route::get('/reports/economics', [ReportController::class, 'exportEconomics'])->name('reports.economics');
    });
