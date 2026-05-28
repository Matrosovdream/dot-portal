<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\References\ReferencesController;

Route::prefix('references')->name('references.')->group(function () {
    Route::get('/bundle',                  [ReferencesController::class, 'bundle'])->name('bundle');
    Route::get('/states',                  [ReferencesController::class, 'states'])->name('states');
    Route::get('/driver-types',            [ReferencesController::class, 'driverTypes'])->name('driver-types');
    Route::get('/license-types',           [ReferencesController::class, 'licenseTypes'])->name('license-types');
    Route::get('/license-endorsements',    [ReferencesController::class, 'licenseEndorsements'])->name('license-endorsements');
    Route::get('/vehicle-ownership-types', [ReferencesController::class, 'vehicleOwnershipTypes'])->name('vehicle-ownership-types');
    Route::get('/vehicle-unit-types',      [ReferencesController::class, 'vehicleUnitTypes'])->name('vehicle-unit-types');
    Route::get('/query-prices',            [ReferencesController::class, 'queryPrices'])->name('query-prices');
    Route::get('/payment-methods',         [ReferencesController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('/request-statuses',        [ReferencesController::class, 'requestStatuses'])->name('request-statuses');
    Route::get('/order-statuses',          [ReferencesController::class, 'orderStatuses'])->name('order-statuses');
    Route::get('/service-groups',          [ReferencesController::class, 'serviceGroups'])->name('service-groups');
});
