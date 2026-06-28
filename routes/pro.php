<?php

use App\Http\Controllers\Pro\Agency\DashboardController as AgencyDashboardController;
use App\Http\Controllers\Pro\Agency\OnboardingController;
use App\Http\Controllers\Pro\Auth\LoginController;
use App\Http\Controllers\Pro\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('pro')
    ->name('pro.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Guest
        |--------------------------------------------------------------------------
        */

        Route::middleware('pro.guest')->group(function () {

            Route::get('/login', [LoginController::class, 'index'])
                ->name('login');

            Route::post('/login/agency', [LoginController::class, 'agency'])
                ->name('agency.login');

            Route::post('/login/employee', [LoginController::class, 'employee'])
                ->name('employee.login');

        });

        /*
        |--------------------------------------------------------------------------
        | Authenticated
        |--------------------------------------------------------------------------
        */

        Route::middleware('pro.auth')->group(function () {

            Route::post('/logout', [LoginController::class, 'logout'])
                ->name('logout');

            Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
                ->name('notifications.mark-all-read');

            Route::delete('/notifications/clear', [NotificationController::class, 'clear'])
                ->name('notifications.clear');

        });

        /*
        |--------------------------------------------------------------------------
        | Agency
        |--------------------------------------------------------------------------
        */

        Route::prefix('agency')
            ->name('agency.')
            ->middleware('auth:pro_agency')
            ->group(function () {

                Route::get('/', [AgencyDashboardController::class, 'index'])
                    ->name('dashboard');

                Route::get('/onboarding', [OnboardingController::class, 'index'])
                    ->name('onboarding.index');

                Route::get('/onboarding/create', [OnboardingController::class, 'create'])
                    ->name('onboarding.create');

                Route::post('/onboarding', [OnboardingController::class, 'store'])
                    ->name('onboarding.store');

                Route::get('/onboarding/{employee}/edit', [OnboardingController::class, 'edit'])
                    ->name('onboarding.edit');

                Route::put('/onboarding/{employee}', [OnboardingController::class, 'update'])
                    ->name('onboarding.update');

                Route::post('/onboarding/{employee}/reset-pin', [OnboardingController::class, 'resetPin'])
                    ->name('onboarding.reset-pin');

            });

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        Route::prefix('employee')
            ->name('employee.')
            ->middleware('auth:pro_employee')
            ->group(function () {

                Route::get('/', [EmployeeDashboardController::class, 'index'])
                    ->name('dashboard');

            });

    });
