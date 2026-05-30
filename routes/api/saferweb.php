<?php

use App\Http\Controllers\Api\V1\Saferweb\SaferwebController;
use Illuminate\Support\Facades\Route;

Route::prefix('saferweb')->name('saferweb.')->group(function () {
    Route::get('inspections', [SaferwebController::class, 'inspectionsIndex'])->name('inspections.index');
    Route::get('inspections/{inspection}', [SaferwebController::class, 'inspectionShow'])->name('inspections.show');
    Route::get('crashes', [SaferwebController::class, 'crashesIndex'])->name('crashes.index');
    Route::get('crashes/{crash}', [SaferwebController::class, 'crashShow'])->name('crashes.show');
});
