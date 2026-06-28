<?php

use App\Http\Controllers\Agency\AnalyticsController;
use App\Http\Controllers\Agency\Auth\AgencyLoginController;
use App\Http\Controllers\Agency\Auth\AgencyRegisterController;
use App\Http\Controllers\Agency\JobPostController;
use App\Http\Controllers\Agency\WorkflowTemplateController;
use App\Http\Controllers\Agency\WorkflowTemplateStepController;
use App\Http\Controllers\InterviewCalendarController;
use App\Http\Controllers\Agency\JobApplicationController as AgencyJobApplicationController;
use App\Http\Controllers\Agency\JobOfferController as AgencyJobOfferController;
use App\Http\Controllers\Guard\JobApplicationController as GuardJobApplicationController;
use App\Http\Controllers\Guard\JobOfferController as GuardJobOfferController;
use App\Http\Controllers\Guard\Auth\GuardLoginController;
use App\Http\Controllers\Guard\Auth\GuardRegisterController;
use App\Http\Controllers\Guard\ProfileController;
use App\Http\Controllers\Pro\ProController;
use App\Http\Controllers\Pro\ProLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\JobController;
use App\Http\Controllers\Public\JobApplicationController;
use App\Http\Controllers\Public\SavedJobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/jobs', [JobController::class, 'index'])
    ->name('jobs.index');

Route::post('/jobs/{jobPost}/apply', [JobApplicationController::class, 'store'])
    ->name('jobs.apply')
    ->middleware(['auth', 'role:applicant']);

Route::post('/jobs/{jobPost}/toggle-save', [SavedJobController::class, 'toggle'])
    ->name('jobs.toggle-save')
    ->middleware(['auth', 'role:applicant']);

Route::middleware('auth')->group(function () {
    Route::get('/interviews/{interview}/calendar', [InterviewCalendarController::class, 'download'])
        ->name('interviews.calendar')
        ->middleware('role:applicant|agency');

    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read')
        ->middleware('role:applicant|agency');

    Route::delete('/notifications/clear', [NotificationController::class, 'clear'])
        ->name('notifications.clear')
        ->middleware('role:applicant|agency');
});

Route::get('/login', [GuardLoginController::class, 'create'])
    ->name('login');

/*
|--------------------------------------------------------------------------
| Guard Portal
|--------------------------------------------------------------------------
*/

Route::prefix('applicant')
    ->name('applicant.')
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login', [GuardLoginController::class, 'create'])
                ->name('login');

            Route::post('/login', [GuardLoginController::class, 'store'])
                ->name('login.store');

            Route::get('/register', [GuardRegisterController::class, 'create'])
                ->name('register');

            Route::post('/register', [GuardRegisterController::class, 'store'])
                ->name('register.store');
        });

        Route::middleware(['auth', 'role:applicant'])->group(function () {
            Route::post('/logout', [GuardLoginController::class, 'destroy'])
                ->name('logout');

            Route::get('/dashboard', [\App\Http\Controllers\Guard\DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/home', function() {
                return redirect()->route('applicant.dashboard');
            })->name('home');

            Route::get('/profile/preview', [ProfileController::class, 'preview'])
                ->name('profile.preview');

            Route::get('/profile/{section?}', [ProfileController::class, 'show'])
                ->name('profile.show');

            Route::post('/profile/basic-information', [ProfileController::class, 'updateBasicInformation'])
                ->name('profile.update-basic-information');

            Route::post('/profile/contact-details', [ProfileController::class, 'updateContactDetails'])
                ->name('profile.update-contact-details');

            Route::post('/profile/physical-details', [ProfileController::class, 'updatePhysicalDetails'])
                ->name('profile.update-physical-details');

            Route::post('/profile/emergency-contacts', [ProfileController::class, 'storeEmergencyContact'])
                ->name('profile.store-emergency-contact');

            Route::patch('/profile/emergency-contacts/{contact}', [ProfileController::class, 'updateEmergencyContact'])
                ->name('profile.update-emergency-contact');

            Route::delete('/profile/emergency-contacts/{contact}', [ProfileController::class, 'deleteEmergencyContact'])
                ->name('profile.delete-emergency-contact');

            Route::post('/profile/education', [ProfileController::class, 'storeEducation'])
                ->name('profile.store-education');

            Route::patch('/profile/education/{education}', [ProfileController::class, 'updateEducation'])
                ->name('profile.update-education');

            Route::delete('/profile/education/{education}', [ProfileController::class, 'deleteEducation'])
                ->name('profile.delete-education');

            Route::post('/profile/certifications', [ProfileController::class, 'storeCertification'])
                ->name('profile.store-certification');

            Route::patch('/profile/certifications/{certification}', [ProfileController::class, 'updateCertification'])
                ->name('profile.update-certification');

            Route::delete('/profile/certifications/{certification}', [ProfileController::class, 'deleteCertification'])
                ->name('profile.delete-certification');

            Route::patch('/profile/firearm-qualification', [ProfileController::class, 'updateFirearmQualification'])
                ->name('profile.update-firearm-qualification');

            Route::post('/profile/work-experience', [ProfileController::class, 'storeWorkExperience'])
                ->name('profile.store-work-experience');

            Route::patch('/profile/work-experience/{experience}', [ProfileController::class, 'updateWorkExperience'])
                ->name('profile.update-work-experience');

            Route::delete('/profile/work-experience/{experience}', [ProfileController::class, 'deleteWorkExperience'])
                ->name('profile.delete-work-experience');

            Route::post('/profile/licenses', [ProfileController::class, 'storeLicense'])
                ->name('profile.store-license');

            Route::patch('/profile/licenses/{license}', [ProfileController::class, 'updateLicense'])
                ->name('profile.update-license');

            Route::delete('/profile/licenses/{license}', [ProfileController::class, 'deleteLicense'])
                ->name('profile.delete-license');

            Route::post('/profile/skills', [ProfileController::class, 'storeSkill'])
                ->name('profile.store-skill');

            Route::patch('/profile/skills/{skill}', [ProfileController::class, 'updateSkill'])
                ->name('profile.update-skill');

            Route::delete('/profile/skills/{skill}', [ProfileController::class, 'deleteSkill'])
                ->name('profile.delete-skill');

            Route::post('/profile/languages', [ProfileController::class, 'storeLanguage'])
                ->name('profile.store-language');

            Route::patch('/profile/languages/{language}', [ProfileController::class, 'updateLanguage'])
                ->name('profile.update-language');

            Route::delete('/profile/languages/{language}', [ProfileController::class, 'deleteLanguage'])
                ->name('profile.delete-language');

            Route::post('/profile/trainings', [ProfileController::class, 'storeTraining'])
                ->name('profile.store-training');

            Route::patch('/profile/trainings/{training}', [ProfileController::class, 'updateTraining'])
                ->name('profile.update-training');

            Route::delete('/profile/trainings/{training}', [ProfileController::class, 'deleteTraining'])
                ->name('profile.delete-training');

            Route::post('/profile/specializations', [ProfileController::class, 'storeSpecialization'])
                ->name('profile.store-specialization');

            Route::delete('/profile/specializations/{specialization}', [ProfileController::class, 'deleteSpecialization'])
                ->name('profile.delete-specialization');

            Route::post('/profile/clearances', [ProfileController::class, 'storeClearance'])
                ->name('profile.store-clearance');

            Route::patch('/profile/clearances/{clearance}', [ProfileController::class, 'updateClearance'])
                ->name('profile.update-clearance');

            Route::delete('/profile/clearances/{clearance}', [ProfileController::class, 'deleteClearance'])
                ->name('profile.delete-clearance');

            Route::post('/profile/medicals', [ProfileController::class, 'storeMedical'])
                ->name('profile.store-medical');

            Route::patch('/profile/medicals/{medical}', [ProfileController::class, 'updateMedical'])
                ->name('profile.update-medical');

            Route::delete('/profile/medicals/{medical}', [ProfileController::class, 'deleteMedical'])
                ->name('profile.delete-medical');

            Route::post('/profile/identifications', [ProfileController::class, 'storeIdentification'])
                ->name('profile.store-identification');

            Route::patch('/profile/identifications/{identification}', [ProfileController::class, 'updateIdentification'])
                ->name('profile.update-identification');

            Route::delete('/profile/identifications/{identification}', [ProfileController::class, 'deleteIdentification'])
                ->name('profile.delete-identification');

            Route::post('/profile/documents', [ProfileController::class, 'updateDocuments'])
                ->name('profile.update-documents');

            Route::get('/applications', [GuardJobApplicationController::class, 'index'])
                ->name('applications.index');

            Route::get('/applications/{application}', [GuardJobApplicationController::class, 'show'])
                ->name('applications.show');

            Route::get('/applications/{application}/messages', [ConversationController::class, 'show'])
                ->name('applications.messages');

            Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage'])
                ->name('conversations.messages.send');

            Route::post('/offers/{offer}/accept', [GuardJobOfferController::class, 'accept'])
                ->name('offers.accept');

            Route::post('/offers/{offer}/decline', [GuardJobOfferController::class, 'decline'])
                ->name('offers.decline');

            Route::get('/offers/{offer}/download', [GuardJobOfferController::class, 'downloadPdf'])
                ->name('offers.download');
        });
    });

/*
|--------------------------------------------------------------------------
| PRO Guard On-Duty App
|--------------------------------------------------------------------------
*/

Route::prefix('pro')
    ->name('pro')
    ->group(function () {
        Route::get('/login', [ProLoginController::class, 'create'])
            ->name('login');

        Route::post('/login', [ProLoginController::class, 'store'])
            ->name('login.store');

        Route::get('/', [ProController::class, 'index'])
            ->name('index');

        Route::post('/logout', [ProLoginController::class, 'destroy'])
            ->name('logout');
    });

/*
|--------------------------------------------------------------------------
| Agency Portal
|--------------------------------------------------------------------------
*/

Route::prefix('agency')
    ->name('agency.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Guest
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest')->group(function () {

            Route::get('/login', [AgencyLoginController::class, 'create'])
                ->name('login');

            Route::post('/login', [AgencyLoginController::class, 'store'])
                ->name('login.store');

            Route::get('/register', [AgencyRegisterController::class, 'create'])
                ->name('register');

            Route::post('/register', [AgencyRegisterController::class, 'store'])
                ->name('register.store');
        });

        /*
        |--------------------------------------------------------------------------
        | Authenticated
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth', 'role:agency'])->group(function () {

            Route::post('/logout', [AgencyLoginController::class, 'destroy'])
                ->name('logout');

            Route::get('/dashboard', [\App\Http\Controllers\Agency\DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/analytics', [AnalyticsController::class, 'index'])
                ->name('analytics');

            Route::prefix('job-posts')
                ->name('job-posts.')
                ->group(function () {

                    Route::get('/', [JobPostController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [JobPostController::class, 'create'])
                        ->name('create');

                    Route::post('/', [JobPostController::class, 'store'])
                        ->name('store');

                    Route::get('/{jobPost}/edit', [JobPostController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{jobPost}', [JobPostController::class, 'update'])
                        ->name('update');

                    Route::delete('/{jobPost}', [JobPostController::class, 'destroy'])
                        ->name('destroy');
                });

            Route::resource('workflow-templates', WorkflowTemplateController::class);
            Route::post('workflow-templates/{workflowTemplate}/steps', [WorkflowTemplateStepController::class, 'store'])
                ->name('workflow-templates.steps.store');
            Route::delete('workflow-templates/{workflowTemplate}/steps/{step}', [WorkflowTemplateStepController::class, 'destroy'])
                ->name('workflow-templates.steps.destroy');
            Route::post('workflow-templates/{workflowTemplate}/steps/reorder', [WorkflowTemplateStepController::class, 'reorder'])
                ->name('workflow-templates.steps.reorder');

            Route::get('/applications', [AgencyJobApplicationController::class, 'index'])
                ->name('applications.index');

            Route::get('/applications/{application}', [AgencyJobApplicationController::class, 'show'])
                ->name('applications.show');

            Route::get('/kanban', [AgencyJobApplicationController::class, 'kanban'])
                ->name('applications.kanban');

            Route::post('/applications/{application}/move', [AgencyJobApplicationController::class, 'move'])
                ->name('applications.move');
            Route::get('/applications/{application}/move', function ($application) {
                return redirect()->route('agency.applications.show', $application);
            });

            Route::post('/applications/{application}/interviews', [\App\Http\Controllers\Agency\InterviewController::class, 'store'])
                ->name('applications.interviews.store');
            Route::get('/applications/{application}/interviews', function ($application) {
                return redirect()->route('agency.applications.show', $application);
            });

            Route::get('/applications/{application}/messages', [ConversationController::class, 'show'])
                ->name('applications.messages');

            Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage'])
                ->name('conversations.messages.send');

            Route::post('/applications/{application}/offers', [AgencyJobOfferController::class, 'store'])
                ->name('applications.offers.store');
            Route::get('/applications/{application}/offers', function ($application) {
                return redirect()->route('agency.applications.show', $application);
            });

            Route::post('/offers/{offer}/send', [AgencyJobOfferController::class, 'send'])
                ->name('offers.send');
            Route::get('/offers/{offer}/send', function ($offer) {
                return redirect()->route('agency.dashboard');
            });

            Route::post('/offers/{offer}/upload-pdf', [AgencyJobOfferController::class, 'uploadPdf'])
                ->name('offers.upload-pdf');
            Route::get('/offers/{offer}/upload-pdf', function ($offer) {
                return redirect()->route('agency.dashboard');
            });

            Route::get('/guards/{guardProfile}', [AgencyJobApplicationController::class, 'showGuardProfile'])
                ->name('guard-profile.show');
        });
    });
