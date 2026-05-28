<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Profile\ProfileController;
use App\Http\Controllers\Api\V1\Profile\ProfileAddressController;
use App\Http\Controllers\Api\V1\Profile\ProfileCompanyController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/',       [ProfileController::class, 'show'])->name('show');
    Route::put('/',       [ProfileController::class, 'update'])->name('update');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::put('address', [ProfileAddressController::class, 'update'])->name('address.update');
    Route::get('company', [ProfileCompanyController::class, 'show'])->name('company.show');
    Route::put('company', [ProfileCompanyController::class, 'update'])->name('company.update');
});
