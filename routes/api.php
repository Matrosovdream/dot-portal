<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {

    Route::get('/ping', function () {
        return response()->json([
            'ok' => true,
            'time' => now()->toIso8601String(),
        ]);
    })->name('ping');

    /*
    |-----------------------------------------------------------------------
    | Each step appends its route file here once it lands. Order:
    |   02 → routes/api/auth.php
    |   03 → routes/api/profile.php
    |   04 → routes/api/references.php, routes/api/globals.php
    |   05 → routes/api/dashboard.php
    |   06 → routes/api/drivers.php
    |   07 → routes/api/vehicles.php, routes/api/insurance-vehicles.php
    |   08 → routes/api/admin/services.php, /service-fields.php, /service-groups.php
    |   09 → routes/api/service-requests.php, routes/api/admin/requests.php
    |   10 → routes/api/subscription.php, routes/api/admin/{sub-plans,sub-requests,sub-manager,plan-fees}.php
    |   13 → routes/api/notifications.php, routes/api/todo.php, routes/api/search.php
    |-----------------------------------------------------------------------
    */

    require __DIR__.'/api/auth.php';

    // Authenticated — references + globals
    Route::middleware('auth:sanctum')->group(function () {
        require __DIR__.'/api/references.php';
        require __DIR__.'/api/globals.php';
    });

    // Authenticated + active user — dashboard home + drivers + vehicles + shell endpoints
    Route::middleware(['auth:sanctum', 'user.isActive'])->group(function () {
        require __DIR__.'/api/dashboard.php';
        require __DIR__.'/api/drivers.php';
        require __DIR__.'/api/orders.php';
        require __DIR__.'/api/files.php';
        require __DIR__.'/api/documents.php';
        require __DIR__.'/api/vehicles.php';
        require __DIR__.'/api/insurance-vehicles.php';
        require __DIR__.'/api/notifications.php';
        require __DIR__.'/api/todo.php';
        require __DIR__.'/api/search.php';
    });

    // Authenticated, active-user, driver/company only — profile + service-requests + subscription
    Route::middleware(['auth:sanctum', 'user.isActive', 'hasRole:driver,company'])->group(function () {
        require __DIR__.'/api/profile.php';
        require __DIR__.'/api/service-requests.php';
        require __DIR__.'/api/subscription.php';
        require __DIR__.'/api/saferweb.php';
    });

    // Admin/Manager — service catalog admin + admin requests + subscriptions admin
    Route::middleware(['auth:sanctum', 'user.isActive', 'hasRole:admin,manager'])
        ->prefix('admin')->name('admin.')->group(function () {
            require __DIR__.'/api/admin/services.php';
            require __DIR__.'/api/admin/service-fields.php';
            require __DIR__.'/api/admin/service-groups.php';
            require __DIR__.'/api/admin/requests.php';
            require __DIR__.'/api/admin/sub-plans.php';
            require __DIR__.'/api/admin/sub-requests.php';
            require __DIR__.'/api/admin/sub-manager.php';
            require __DIR__.'/api/admin/plan-fees.php';
            require __DIR__.'/api/admin/gateways.php';
            require __DIR__.'/api/admin/settings.php';

            // Admin-only (manager excluded)
            Route::middleware('hasRole:admin')->group(function () {
                require __DIR__.'/api/admin/users.php';
                require __DIR__.'/api/admin/notifications.php';
            });
        });

});
