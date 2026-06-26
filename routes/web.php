<?php

use App\Http\Controllers\Guard\Auth\GuardLoginController;
use App\Http\Controllers\Guard\Auth\GuardRegisterController;
use App\Http\Controllers\Guard\ProfileController;
use App\Http\Controllers\Pro\ProController;
use App\Http\Controllers\Pro\ProLoginController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
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
