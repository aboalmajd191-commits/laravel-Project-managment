<?php

// routes/data-entry.php

declare(strict_types=1);

use App\Http\Controllers\DataEntry\TrainingController;
use App\Http\Controllers\DataEntry\EconomicController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:data_entry', 'account.active'])
    ->prefix('entry')
    ->name('data_entry.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('entry.dashboard');
        })->name('dashboard');

        // My Submissions (Personal Submissions List)
        Route::get('/submissions', [\App\Http\Controllers\DataEntry\UserSubmissionController::class, 'index'])
            ->name('submissions.index');

        // Services Module (Detail Filling Only)
        Route::prefix('services')->name('services.')->group(function () {
            Route::resource('training', TrainingController::class)->only(['index', 'edit', 'update']);
            Route::resource('economic_empowerment', EconomicController::class)->only(['index', 'edit', 'update']);
            Route::resource('awareness_workshop', \App\Http\Controllers\DataEntry\AwarenessWorkshopController::class)->only(['index', 'edit', 'update']);
            Route::resource('legal_consultation', \App\Http\Controllers\DataEntry\LegalConsultationController::class)->only(['index', 'edit', 'update']);
            Route::resource('psychological_consultation', \App\Http\Controllers\DataEntry\PsychologicalConsultationController::class)->only(['index', 'edit', 'update']);
            Route::resource('judicial_representation', \App\Http\Controllers\DataEntry\JudicialRepresentationController::class)->only(['index', 'edit', 'update']);
            Route::resource('legal_representation', \App\Http\Controllers\DataEntry\LegalRepresentationController::class)->only(['index', 'edit', 'update']);
            Route::resource('mediation', \App\Http\Controllers\DataEntry\MediationController::class)->only(['index', 'edit', 'update']);
            Route::resource('individual_support_session', \App\Http\Controllers\DataEntry\IndividualSupportSessionController::class)->only(['index', 'edit', 'update']);
            Route::post('individual_support_session/submit-all-drafts', [\App\Http\Controllers\DataEntry\IndividualSupportSessionController::class, 'submitAllDrafts'])
                ->name('individual_support_session.submit_all_drafts');
            Route::post('individual_support_session/{individual_support_session}/store-child', [\App\Http\Controllers\DataEntry\IndividualSupportSessionController::class, 'storeChild'])
                ->name('individual_support_session.store_child');
            Route::resource('group_support_session', \App\Http\Controllers\DataEntry\GroupSupportSessionController::class)->only(['index', 'edit', 'update']);
            
            // Blank / Development Services
            $blanks = ['cash_assistance', 'in_kind_assistance', 'community_initiatives', 'support_sponsorship'];
            foreach ($blanks as $service) {
                Route::get("/{$service}", [\App\Http\Controllers\DataEntry\BlankServiceController::class, 'index'])
                    ->defaults('service_name', $service)
                    ->name("{$service}.index");
            }
        });
    });

