<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\ServiceGroupController;

Route::prefix('service-groups')->name('service-groups.')->group(function () {
    Route::get('/',         [ServiceGroupController::class, 'index'])->name('index');
    Route::post('/',        [ServiceGroupController::class, 'store'])->name('store');
    Route::get('{group}',   [ServiceGroupController::class, 'show'])->name('show');
    Route::put('{group}',   [ServiceGroupController::class, 'update'])->name('update');
    Route::delete('{group}',[ServiceGroupController::class, 'destroy'])->name('destroy');
});
