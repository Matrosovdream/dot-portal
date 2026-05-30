<?php

use App\Http\Controllers\Api\V1\Admin\Users\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('index');
    Route::post('/', [AdminUserController::class, 'store'])->name('store');
    Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
    Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
});
