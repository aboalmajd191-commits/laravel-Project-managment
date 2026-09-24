<?php

use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\ProjectController;
use App\Http\Controllers\Manager\ApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:project_manager', 'account.active'])
    ->prefix('project_manager')
    ->name('project_manager.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/projects/{project}/update-status', [\App\Http\Controllers\Manager\ProjectStatusController::class, 'updateStatus'])->name('projects.update_status');
        
        // Projects CRUD
        Route::resource('projects', ProjectController::class);
        
        // Approvals
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{type}/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{type}/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{type}/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

        // Profile
        Route::get('/profile', function() { return view('manager.profile'); })->name('profile');

        // Services Management (Shell Creation & Data Entry)
        Route::prefix('services')->name('services.')->group(function () {
            Route::resource('training', \App\Http\Controllers\Manager\TrainingController::class);
            Route::resource('economic_empowerment', \App\Http\Controllers\Manager\EconomicController::class);
            Route::resource('awareness_workshop', \App\Http\Controllers\Manager\AwarenessWorkshopController::class);
            Route::resource('legal_consultation', \App\Http\Controllers\Manager\LegalConsultationController::class);
            Route::resource('psychological_consultation', \App\Http\Controllers\Manager\PsychologicalConsultationController::class);
            Route::resource('judicial_representation', \App\Http\Controllers\Manager\JudicialRepresentationController::class);
            Route::resource('legal_representation', \App\Http\Controllers\Manager\LegalRepresentationController::class);
            Route::resource('mediation', \App\Http\Controllers\Manager\MediationController::class);
            Route::resource('individual_support_session', \App\Http\Controllers\Manager\IndividualSupportSessionController::class);
            Route::resource('group_support_session', \App\Http\Controllers\Manager\GroupSupportSessionController::class);
            
            // Blank / Development Services
            $blanks = ['cash_assistance', 'in_kind_assistance', 'community_initiatives', 'support_sponsorship'];
            foreach ($blanks as $service) {
                Route::get("/{$service}", [\App\Http\Controllers\DataEntry\BlankServiceController::class, 'index'])
                    ->defaults('service_name', $service)
                    ->name("{$service}.index");
            }
        });
    });

