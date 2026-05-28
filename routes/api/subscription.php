<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Subscriptions\SubscriptionController;

Route::prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/',     [SubscriptionController::class, 'show'])->name('show');
    Route::put('/',     [SubscriptionController::class, 'update'])->name('update');
    Route::post('cancel',[SubscriptionController::class, 'cancel'])->name('cancel');
});
