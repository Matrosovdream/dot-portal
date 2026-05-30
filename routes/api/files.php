<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Files\FileController;

Route::prefix('files')->name('files.')->group(function () {
    Route::post('/',                [FileController::class, 'store'])->name('store');
    Route::get('{file}',            [FileController::class, 'show'])->name('show');
    Route::get('{file}/download',   [FileController::class, 'download'])->name('download');
});
