<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\InsuranceVehicles\InsuranceVehicleController;

Route::prefix('insurance-vehicles')->name('insurance-vehicles.')->group(function () {
    Route::get('/',              [InsuranceVehicleController::class, 'index'])->name('index');
    Route::post('/',             [InsuranceVehicleController::class, 'store'])->name('store');
    Route::get('{insurance}',    [InsuranceVehicleController::class, 'show'])->name('show');
    Route::put('{insurance}',    [InsuranceVehicleController::class, 'update'])->name('update');
    Route::delete('{insurance}', [InsuranceVehicleController::class, 'destroy'])->name('destroy');
});
