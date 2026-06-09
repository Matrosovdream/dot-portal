<?php

use App\Http\Controllers\Api\V1\Admin\Companies\AdminCompanyController;
use Illuminate\Support\Facades\Route;

// Admin + manager company roster (carrier profiles). Lives in the shared
// hasRole:admin,manager group, so managers get access too.
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/',            [AdminCompanyController::class, 'index'])->name('index');
    Route::post('/',           [AdminCompanyController::class, 'store'])->name('store');
    Route::get('{company}',    [AdminCompanyController::class, 'show'])->name('show');
    Route::put('{company}',    [AdminCompanyController::class, 'update'])->name('update');
    Route::delete('{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
});
