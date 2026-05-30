<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Orders\OrderController;

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/',                  [OrderController::class, 'index'])->name('index');
    Route::get('{order}',            [OrderController::class, 'show'])->name('show');
    Route::get('{order}/pay',        [OrderController::class, 'payForm'])->name('pay.form');
    Route::post('{order}/pay',       [OrderController::class, 'pay'])->name('pay');
    Route::get('{order}/payments',   [OrderController::class, 'payments'])->name('payments');
});
