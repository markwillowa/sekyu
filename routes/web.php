<?php

use App\Http\Controllers\Agency\Auth\AgencyLoginController;
use App\Http\Controllers\Agency\Auth\AgencyRegisterController;
use App\Http\Controllers\Agency\JobPostController;
use App\Http\Controllers\Guard\Auth\GuardLoginController;
use App\Http\Controllers\Guard\Auth\GuardRegisterController;
use App\Http\Controllers\Guard\ProfileController;
use App\Http\Controllers\Pro\ProController;
use App\Http\Controllers\Pro\ProLoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::redirect('/login', '/pro/login')
    ->name('login');

/*
|--------------------------------------------------------------------------
| Guard Portal
|--------------------------------------------------------------------------
*/

Route::prefix('guard')
    ->name('guard.')
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

        Route::middleware('auth')->group(function () {
            Route::post('/logout', [GuardLoginController::class, 'destroy'])
                ->name('logout');

            Route::view('/home', 'guard.home')
                ->name('home');

            Route::get('/profile/preview', [ProfileController::class, 'preview'])
                ->name('profile.preview');

            Route::get('/profile/{section?}', [ProfileController::class, 'show'])
                ->name('profile.show');

            Route::get('/agency/dashboard', function () {
                return view('agency.dashboard');
            })->name('agency.dashboard');
        });
    });

/*
|--------------------------------------------------------------------------
| PRO Guard On-Duty App
|--------------------------------------------------------------------------
*/

Route::prefix('pro')
    ->name('pro.')
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

        Route::middleware('auth')->group(function () {

            Route::post('/logout', [AgencyLoginController::class, 'destroy'])
                ->name('logout');

            Route::get('/dashboard', [\App\Http\Controllers\Agency\DashboardController::class, 'index'])
                ->name('dashboard');

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
        });
    });
