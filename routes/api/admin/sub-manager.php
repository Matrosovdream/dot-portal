<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\SubManagerController;

Route::prefix('user-subscriptions')->name('user-subscriptions.')->group(function () {
    Route::get('/',                       [SubManagerController::class, 'index'])->name('index');
    Route::post('/',                      [SubManagerController::class, 'store'])->name('store');
    Route::get('{userSubscription}',      [SubManagerController::class, 'show'])->name('show');
    Route::put('{userSubscription}',      [SubManagerController::class, 'update'])->name('update');
    Route::delete('{userSubscription}',   [SubManagerController::class, 'destroy'])->name('destroy');
    Route::post('{userSubscription}/send-onetime',      [SubManagerController::class, 'sendOnetime'])->name('send-onetime');
    Route::post('{userSubscription}/send-payment-link', [SubManagerController::class, 'sendPaymentLink'])->name('send-payment-link');
});
