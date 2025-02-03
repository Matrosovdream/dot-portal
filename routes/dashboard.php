<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboard\DashboardHomeController;
use App\Http\Controllers\Dashboard\DashboardUsersController;
use App\Http\Controllers\Dashboard\DashboardSettingsController;
use App\Http\Controllers\Dashboard\DashboardGatewaysController;
use App\Http\Controllers\Dashboard\DashboardProfileController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ServiceFieldsController;
use App\Http\Controllers\Dashboard\AdminRequestController;





Route::group(
    [
        'as' => '',
        'prefix' =>'dashboard',
        'namespace' => '', 
        'middleware' => ['auth', /*'hasRole:admin,manager'*/]
    ],
    function(){
    
    // Home dashboard page
    Route::get('/', [DashboardHomeController::class, 'index'])->name('dashboard.home');

    // Admin routes
    Route::middleware('hasRole:admin,manager')->group(function () {

        Route::middleware('hasRole:admin')->group(function() {

            // User
            Route::get('users', [DashboardUsersController::class, 'index'])->name('dashboard.users.index');
            Route::get('users/create', [DashboardUsersController::class, 'create'])->name('dashboard.users.create');
            Route::post('users', [DashboardUsersController::class, 'store'])->name('dashboard.users.store');
            Route::get('users/{user_id}', [DashboardUsersController::class, 'show'])->name('dashboard.users.show');
            Route::post('users/{user_id}', [DashboardUsersController::class, 'update'])->name('dashboard.users.update');
            Route::delete('users/{user_id}', [DashboardUsersController::class, 'destroy'])->name('dashboard.users.destroy');

        });

        // Payment gateways
        Route::get('gateways', [DashboardGatewaysController::class, 'index'])->name('dashboard.gateways.index');

        // Services
        Route::group(['prefix' => 'services'], function () {
            Route::get('/', [ServiceController::class, 'index'])->name('dashboard.services.index');
            Route::get('create', [ServiceController::class, 'create'])->name('dashboard.services.create');
            Route::post('/', [ServiceController::class, 'store'])->name('dashboard.services.store');
            Route::get('{service}', [ServiceController::class, 'show'])->name('dashboard.services.show');
            Route::post('{service}', [ServiceController::class, 'update'])->name('dashboard.services.update');
            Route::delete('{service}', [ServiceController::class, 'destroy'])->name('dashboard.services.destroy');
        });

        // Service fields
        Route::get('servicefields', [ServiceFieldsController::class, 'index'])->name('dashboard.servicefields.index');

        // Admin request
        Route::get('requests', [AdminRequestController::class, 'index'])->name('dashboard.admin.requests.index');

        // Settings
        Route::get('settings', [DashboardSettingsController::class, 'index'])->name('dashboard.settings.index');
        Route::post('settings', [DashboardSettingsController::class, 'store'])->name('dashboard.settings.store');

    });

    // User routes
    Route::middleware('isUser')->group(function () {

        // dashboard.profile.destroy, dashboard.password.update, dashboard.profile.update

        // User
        Route::get('profile', [DashboardProfileController::class, 'profile'])->name('dashboard.profile');
        Route::post('profile', [DashboardProfileController::class, 'updateProfile'])->name('dashboard.profile.update');
        Route::post('profile/password', [DashboardProfileController::class, 'updatePassword'])->name('dashboard.password.update');
        Route::post('profile/destroy', [DashboardProfileController::class, 'destroy'])->name('dashboard.profile.destroy');

    });

});

