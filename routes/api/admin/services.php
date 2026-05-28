<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\ServiceController;

Route::prefix('services')->name('services.')->group(function () {
    Route::get('/',            [ServiceController::class, 'index'])->name('index');
    Route::post('/',           [ServiceController::class, 'store'])->name('store');
    Route::get('{service}',    [ServiceController::class, 'show'])->name('show');
    Route::put('{service}',    [ServiceController::class, 'update'])->name('update');
    Route::delete('{service}', [ServiceController::class, 'destroy'])->name('destroy');
    Route::post('{service}/status', [ServiceController::class, 'updateStatus'])->name('status');
});
