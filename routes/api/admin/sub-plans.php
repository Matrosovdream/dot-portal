<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\SubPlanController;

Route::prefix('sub-plans')->name('sub-plans.')->group(function () {
    Route::get('/',       [SubPlanController::class, 'index'])->name('index');
    Route::post('/',      [SubPlanController::class, 'store'])->name('store');
    Route::get('{plan}',  [SubPlanController::class, 'show'])->name('show');
    Route::put('{plan}',  [SubPlanController::class, 'update'])->name('update');
    Route::delete('{plan}',[SubPlanController::class, 'destroy'])->name('destroy');
});
