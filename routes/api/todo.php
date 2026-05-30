<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Todo\TodoController;

Route::prefix('todo')->name('todo.')->group(function () {
    Route::get('/',       [TodoController::class, 'index'])->name('index');
    Route::get('company', [TodoController::class, 'company'])->name('company');
    Route::get('vehicle', [TodoController::class, 'vehicle'])->name('vehicle');
    Route::get('driver',  [TodoController::class, 'driver'])->name('driver');
    Route::get('{task}',  [TodoController::class, 'show'])->name('show');
});
