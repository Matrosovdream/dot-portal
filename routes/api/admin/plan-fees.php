<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\PlanFeeController;

Route::prefix('plan-fees')->name('plan-fees.')->group(function () {
    Route::get('/',     [PlanFeeController::class, 'index'])->name('index');
    Route::post('/',    [PlanFeeController::class, 'store'])->name('store');
    Route::get('{fee}', [PlanFeeController::class, 'show'])->name('show');
    Route::put('{fee}', [PlanFeeController::class, 'update'])->name('update');
});
