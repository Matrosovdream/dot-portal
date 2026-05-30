<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Documents\DocumentController;

Route::prefix('documents')->name('documents.')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
});
