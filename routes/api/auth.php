<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\V1\Auth\NewPasswordController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Auth\EmailVerificationController;
use App\Http\Controllers\Api\V1\Auth\OneTimeLoginController;

Route::prefix('auth')->name('auth.')->group(function () {

    // Guest
    Route::middleware('guest')->group(function () {
        Route::post('login',           [LoginController::class, 'store'])->name('login');
        Route::post('register',        [RegisterController::class, 'store'])->name('register');
        Route::post('password/email',  [PasswordResetLinkController::class, 'store'])
            ->middleware('throttle:6,1')->name('password.email');
        Route::post('password/reset',  [NewPasswordController::class, 'store'])->name('password.reset');
        Route::get('login-onetime/{token}', [OneTimeLoginController::class, 'show'])
            ->name('login.onetime');
    });

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me',     [MeController::class, 'show'])->name('me');
        Route::post('logout', [LogoutController::class, 'destroy'])->name('logout');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('email/verify',        [EmailVerificationController::class, 'status'])->name('verification.notice');
        Route::post('email/verify/resend', [EmailVerificationController::class, 'send'])
            ->middleware('throttle:6,1')->name('verification.send');
        Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    });

});
