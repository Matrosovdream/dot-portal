<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\DashboardUsersController;
use App\Http\Controllers\Dashboard\DashboardSettingsController;
use App\Http\Controllers\Dashboard\DashboardGatewaysController;
use App\Http\Controllers\Dashboard\DashboardProfileController;
use App\Http\Controllers\Dashboard\ServiceAdminController;
use App\Http\Controllers\Dashboard\ServiceFieldsController;
use App\Http\Controllers\Dashboard\AdminRequestController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\NotificationsAdminController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\SubscriptionUserController;
use App\Http\Controllers\Dashboard\ServiceGroupsController;
use App\Http\Controllers\Dashboard\RequestUserController;
use App\Http\Controllers\Dashboard\RequestAdminController;
use App\Http\Controllers\Dashboard\DocumentsController;

Route::group([
    'prefix' => 'dashboard',
    'as'       => 'dashboard.',
    'middleware' => ['auth']
], function () {

    // Home dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Notifications
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications');

    // Drivers
    Route::prefix('my-drivers')->name('drivers.')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('create', [DriverController::class, 'create'])->name('create');
        Route::post('/', [DriverController::class, 'store'])->name('store');
        Route::get('{driver}', [DriverController::class, 'show'])->name('show');
        Route::post('{driver}', [DriverController::class, 'update'])->name('update');
        Route::delete('{driver}', [DriverController::class, 'destroy'])->name('destroy');

        // Driver subroutes
        Route::prefix('{driver}')->name('show.')->group(function () {
            Route::get('profile', [DriverController::class, 'profile'])->name('profile');
            Route::post('profile', [DriverController::class, 'updateProfile'])->name('profile.update');
            Route::get('license', [DriverController::class, 'license'])->name('license');
            Route::post('license', [DriverController::class, 'updateLicense'])->name('license.update');
            Route::get('address', [DriverController::class, 'address'])->name('address');
            Route::post('address', [DriverController::class, 'updateAddress'])->name('address.update');
            Route::get('medical-card', [DriverController::class, 'medicalCard'])->name('medicalcard');
            Route::post('medical-card', [DriverController::class, 'updateMedicalCard'])->name('medicalcard.update');
            Route::get('logs', [DriverController::class, 'logs'])->name('logs');
        });
    });

    // Admin routes
    Route::middleware('hasRole:admin')->group(function () {
        Route::prefix('notifications-manager')->name('notifications-manage.')->group(function () {
            Route::get('/', [NotificationsAdminController::class, 'index'])->name('index');
            Route::get('create', [NotificationsAdminController::class, 'create'])->name('create');
            Route::post('/', [NotificationsAdminController::class, 'store'])->name('store');
            Route::get('{service}', [NotificationsAdminController::class, 'show'])->name('show');
            Route::post('{service}', [NotificationsAdminController::class, 'update'])->name('update');
            Route::delete('{service}', [NotificationsAdminController::class, 'destroy'])->name('destroy');
        });
    });

    // Admin/Manager routes
    Route::middleware('hasRole:admin,manager')->group(function () {

        // Admin-only: Users management
        Route::middleware('hasRole:admin')->group(function () {
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [DashboardUsersController::class, 'index'])->name('index');
                Route::get('create', [DashboardUsersController::class, 'create'])->name('create');
                Route::post('/', [DashboardUsersController::class, 'store'])->name('store');
                Route::get('{user_id}', [DashboardUsersController::class, 'show'])->name('show');
                Route::post('{user_id}', [DashboardUsersController::class, 'update'])->name('update');
                Route::delete('{user_id}', [DashboardUsersController::class, 'destroy'])->name('destroy');
            });
        });

        // Payment gateways
        Route::get('gateways', [DashboardGatewaysController::class, 'index'])->name('gateways.index');

        // Services admin
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', [ServiceAdminController::class, 'index'])->name('index');
            Route::get('create', [ServiceAdminController::class, 'create'])->name('create');
            Route::post('/', [ServiceAdminController::class, 'store'])->name('store');
            Route::get('{service}', [ServiceAdminController::class, 'show'])->name('show');
            Route::post('{service}', [ServiceAdminController::class, 'update'])->name('update');
            Route::delete('{service}', [ServiceAdminController::class, 'destroy'])->name('destroy');

            // Service fields
            Route::prefix('{service}/fields')->name('fields.')->group(function () {
                Route::post('/', [ServiceAdminController::class, 'storeField'])->name('store');
                Route::post('{field_id}', [ServiceAdminController::class, 'updateField'])->name('update');
                Route::delete('{field_id}', [ServiceAdminController::class, 'destroyField'])->name('destroy');
            });
        });

        // Standalone Service fields
        Route::prefix('servicefields')->name('servicefields.')->group(function () {
            Route::get('/', [ServiceFieldsController::class, 'index'])->name('index');
            Route::get('create', [ServiceFieldsController::class, 'create'])->name('create');
            Route::post('/', [ServiceFieldsController::class, 'store'])->name('store');
            Route::get('{field_id}', [ServiceFieldsController::class, 'show'])->name('show');
            Route::post('{field_id}', [ServiceFieldsController::class, 'update'])->name('update');
            Route::delete('{field_id}', [ServiceFieldsController::class, 'destroy'])->name('destroy');
        });

        // Service groups
        Route::prefix('servicegroups')->name('servicegroups.')->group(function () {
            Route::get('/', [ServiceGroupsController::class, 'index'])->name('index');
            Route::get('create', [ServiceGroupsController::class, 'create'])->name('create');
            Route::post('/', [ServiceGroupsController::class, 'store'])->name('store');
            Route::get('{group_id}', [ServiceGroupsController::class, 'show'])->name('show');
            Route::post('{group_id}', [ServiceGroupsController::class, 'update'])->name('update');
            Route::delete('{group_id}', [ServiceGroupsController::class, 'destroy'])->name('destroy');
        });

        // Request management
        Route::prefix('request-manage')->name('requestmanage.')->group(function () {
            Route::get('/', [RequestAdminController::class, 'index'])->name('index');
            Route::get('{request_id}', [RequestAdminController::class, 'show'])->name('show');
            Route::post('{request_id}', [RequestAdminController::class, 'update'])->name('update');
            Route::delete('{request_id}', [RequestAdminController::class, 'destroy'])->name('destroy');
        });

        // Admin requests
        Route::get('requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [DashboardSettingsController::class, 'index'])->name('index');
            Route::post('/', [DashboardSettingsController::class, 'store'])->name('store');
        });
    });

    // User routes
    Route::middleware('isUser')->group(function () {
        // Subscription
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/', [SubscriptionUserController::class, 'index'])->name('index');
            Route::post('/', [SubscriptionUserController::class, 'update'])->name('update');
        });

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [DashboardProfileController::class, 'profilePreview'])->name('show');
            Route::post('/', [DashboardProfileController::class, 'updateProfile'])->name('update');
            Route::post('password', [DashboardProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('edit', [DashboardProfileController::class, 'profileEdit'])->name('edit');
            Route::post('edit', [DashboardProfileController::class, 'profileUpdate'])->name('update');
            Route::post('address', [DashboardProfileController::class, 'profileAddressUpdate'])->name('address.update');
            Route::get('company', [DashboardProfileController::class, 'companyEdit'])->name('company.edit');
            Route::post('company', [DashboardProfileController::class, 'companyUpdate'])->name('company.update');
        });
    });

    // Service requests
    Route::prefix('service-request')->name('servicerequest.')->group(function () {
        Route::get('history', [RequestUserController::class, 'history'])->name('history.index');
        Route::get('history/{request_id}', [RequestUserController::class, 'historyShow'])->name('history.show');

        // Group requests
        Route::get('{group}', [RequestUserController::class, 'showGroup'])->name('group');

        // Service forms
        Route::get('{group}/{service}', [RequestUserController::class, 'show'])->name('show');
        Route::post('{group}/{service}', [RequestUserController::class, 'store'])->name('store');

        // Store request
        Route::post('{group}/{service}/store', [RequestUserController::class, 'storeRequest'])->name('store.request');
    });

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentsController::class, 'documents'])->name('index');
    });

});
