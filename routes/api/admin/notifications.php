<?php

use App\Http\Controllers\Api\V1\Admin\AdminNotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications-manage')->name('notifications-manage.')->group(function () {
    Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
    Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
    Route::get('/{notification}', [AdminNotificationController::class, 'show'])->name('show');
    Route::put('/{notification}', [AdminNotificationController::class, 'update'])->name('update');
    Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
});
