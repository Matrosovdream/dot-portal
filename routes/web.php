<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Files
Route::get('file/{file}', [FileController::class, 'download'])->name('file.download');

// Authorization routes
require __DIR__.'/auth.php';

// User routes
require __DIR__.'/user.php';

// Dashboard routes
require __DIR__.'/dashboard.php';
