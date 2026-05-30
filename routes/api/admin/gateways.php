<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\GatewayController;

Route::prefix('gateways')->name('gateways.')->group(function () {
    Route::get('/', [GatewayController::class, 'index'])->name('index');
});
