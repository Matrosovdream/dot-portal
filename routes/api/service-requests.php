<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ServiceRequests\ServiceRequestController;

Route::prefix('service-requests')->name('service-requests.')->group(function () {
    Route::get('/',                 [ServiceRequestController::class, 'index'])->name('index');
    Route::post('/',                [ServiceRequestController::class, 'store'])->name('store');
    Route::get('{serviceRequest}',  [ServiceRequestController::class, 'show'])->name('show');
});
