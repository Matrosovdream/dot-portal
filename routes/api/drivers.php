<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Drivers\DriverController;

Route::prefix('drivers')->name('drivers.')->group(function () {
    Route::get('/',                   [DriverController::class, 'index'])->name('index');
    Route::post('/',                  [DriverController::class, 'store'])->name('store');
    Route::get('{driver}',            [DriverController::class, 'show'])->name('show');
    Route::put('{driver}',            [DriverController::class, 'update'])->name('update');
    Route::delete('{driver}',         [DriverController::class, 'destroy'])->name('destroy');
    Route::post('{driver}/terminate', [DriverController::class, 'terminate'])->name('terminate');
    Route::post('{driver}/send-onetime', [DriverController::class, 'sendOnetime'])->name('send-onetime');
});
