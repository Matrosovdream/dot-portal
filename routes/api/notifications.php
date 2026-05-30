<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Notifications\NotificationController;

Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/',                   [NotificationController::class, 'index'])->name('index');
    Route::post('read-all',           [NotificationController::class, 'markAllRead'])->name('read-all');
    Route::put('{notification}/read', [NotificationController::class, 'markRead'])->name('read');
});
