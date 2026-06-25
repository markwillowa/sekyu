<?php

use App\Http\Controllers\Guard\Auth\GuardLoginController;
use App\Http\Controllers\Guard\Auth\GuardRegisterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('home');

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
        });
    });
