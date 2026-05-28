<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\ServiceFieldController;

Route::prefix('service-fields')->name('service-fields.')->group(function () {
    Route::get('/',         [ServiceFieldController::class, 'index'])->name('index');
    Route::post('/',        [ServiceFieldController::class, 'store'])->name('store');
    Route::get('{field}',   [ServiceFieldController::class, 'show'])->name('show');
    Route::put('{field}',   [ServiceFieldController::class, 'update'])->name('update');
    Route::delete('{field}',[ServiceFieldController::class, 'destroy'])->name('destroy');
});
