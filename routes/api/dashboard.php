<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Dashboard\HomeController;

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/home', [HomeController::class, 'show'])->name('home');
});
