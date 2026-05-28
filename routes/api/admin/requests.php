<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminRequestController;

Route::prefix('requests')->name('requests.')->group(function () {
    Route::get('/',                  [AdminRequestController::class, 'index'])->name('index');
    Route::get('{serviceRequest}',   [AdminRequestController::class, 'show'])->name('show');
    Route::put('{serviceRequest}',   [AdminRequestController::class, 'update'])->name('update');
    Route::post('{serviceRequest}/status', [AdminRequestController::class, 'updateStatus'])->name('status');
    Route::delete('{serviceRequest}',[AdminRequestController::class, 'destroy'])->name('destroy');
});
