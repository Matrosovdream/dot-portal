<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\SubRequestController;

Route::prefix('sub-requests')->name('sub-requests.')->group(function () {
    Route::get('/',                 [SubRequestController::class, 'index'])->name('index');
    Route::post('/',                [SubRequestController::class, 'store'])->name('store');
    Route::get('{subRequest}',      [SubRequestController::class, 'show'])->name('show');
    Route::put('{subRequest}',      [SubRequestController::class, 'update'])->name('update');
    Route::delete('{subRequest}',   [SubRequestController::class, 'destroy'])->name('destroy');
    Route::post('{subRequest}/send-email', [SubRequestController::class, 'sendEmail'])->name('send-email');
});
