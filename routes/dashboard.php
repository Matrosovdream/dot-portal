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


Route::group(
    [
        'as' => '',
        'prefix' => 'dashboard',
        'namespace' => '',
        'middleware' => ['auth', /*'hasRole:admin,manager'*/]
    ],
    function () {

        // Home dashboard page
        Route::get('/', [HomeController::class, 'index'])->name('dashboard.home');

        // Notifications
        Route::get('notifications', [NotificationsController::class, 'index'])->name('dashboard.notifications');


        // Drivers
        Route::group(['prefix' => 'my-drivers'], function () {
            Route::get('/', [DriverController::class, 'index'])->name('dashboard.drivers.index');
            Route::get('create', [DriverController::class, 'create'])->name('dashboard.drivers.create');
            Route::post('/', [DriverController::class, 'store'])->name('dashboard.drivers.store');
            Route::get('{driver}', [DriverController::class, 'show'])->name('dashboard.drivers.show');
            Route::post('{driver}', [DriverController::class, 'update'])->name('dashboard.drivers.update');
            Route::delete('{driver}', [DriverController::class, 'destroy'])->name('dashboard.drivers.destroy');

            // Profile
            Route::get('{driver}/profile', [DriverController::class, 'profile'])->name('dashboard.drivers.show.profile');
            Route::post('{driver}/profile', [DriverController::class, 'updateProfile'])->name('dashboard.drivers.show.profile.update');

            // Driver license
            Route::get('{driver}/license', [DriverController::class, 'license'])->name('dashboard.drivers.show.license');
            Route::post('{driver}/license', [DriverController::class, 'updateLicense'])->name('dashboard.drivers.show.license.update');

            // Address
            Route::get('{driver}/address', [DriverController::class, 'address'])->name('dashboard.drivers.show.address');
            Route::post('{driver}/address', [DriverController::class, 'updateAddress'])->name('dashboard.drivers.show.address.update');

            // Medical card
            Route::get('{driver}/medical-card', [DriverController::class, 'medicalCard'])->name('dashboard.drivers.show.medicalcard');
            Route::post('{driver}/medical-card', [DriverController::class, 'updateMedicalCard'])->name('dashboard.drivers.show.medicalcard.update');

            // Logs
            Route::get('{driver}/logs', [DriverController::class, 'logs'])->name('dashboard.drivers.show.logs');

        });


        // Admin routes
        Route::middleware('hasRole:admin')->group(function () {

            // Notifications manager
            Route::group(['prefix' => 'notifications-manager'], function () {
                Route::get('/', [NotificationsAdminController::class, 'index'])->name('dashboard.notifications-manage.index');
                Route::get('create', [NotificationsAdminController::class, 'create'])->name('dashboard.notifications-manage.create');
                Route::post('/', [NotificationsAdminController::class, 'store'])->name('dashboard.notifications-manage.store');
                Route::get('{service}', [NotificationsAdminController::class, 'show'])->name('dashboard.notifications-manage.show');
                Route::post('{service}', [NotificationsAdminController::class, 'update'])->name('dashboard.notifications-manage.update');
                Route::delete('{service}', [NotificationsAdminController::class, 'destroy'])->name('dashboard.notifications-manage.destroy');
            });

        });

        // Admin/manager routes
        Route::middleware('hasRole:admin,manager')->group(function () {

            Route::middleware('hasRole:admin')->group(function () {

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

            // Services admin
            Route::group(['prefix' => 'services'], function () {
                Route::get('/', [ServiceAdminController::class, 'index'])->name('dashboard.services.index');
                Route::get('create', [ServiceAdminController::class, 'create'])->name('dashboard.services.create');
                Route::post('/', [ServiceAdminController::class, 'store'])->name('dashboard.services.store');
                Route::get('{service}', [ServiceAdminController::class, 'show'])->name('dashboard.services.show');
                Route::post('{service}', [ServiceAdminController::class, 'update'])->name('dashboard.services.update');
                Route::delete('{service}', [ServiceAdminController::class, 'destroy'])->name('dashboard.services.destroy');
            });

            // Service fields
            Route::group(['prefix' => 'servicefields'], function () {
                Route::get('/', [ServiceFieldsController::class, 'index'])->name('dashboard.servicefields.index');
                Route::get('create', [ServiceFieldsController::class, 'create'])->name('dashboard.servicefields.create');
                Route::post('/', [ServiceFieldsController::class, 'store'])->name('dashboard.servicefields.store');
                Route::get('{field_id}', [ServiceFieldsController::class, 'show'])->name('dashboard.servicefields.show');
                Route::post('{field_id}', [ServiceFieldsController::class, 'update'])->name('dashboard.servicefields.update');
                Route::delete('{field_id}', [ServiceFieldsController::class, 'destroy'])->name('dashboard.servicefields.destroy');
            });


            //Route::get('servicefields', [ServiceFieldsController::class, 'index'])->name('dashboard.servicefields.index');
    
            // Admin request
            Route::get('requests', [AdminRequestController::class, 'index'])->name('dashboard.admin.requests.index');

            // Settings
            Route::get('settings', [DashboardSettingsController::class, 'index'])->name('dashboard.settings.index');
            Route::post('settings', [DashboardSettingsController::class, 'store'])->name('dashboard.settings.store');

        });

        // User routes
        Route::middleware('isUser')->group(function () {

            // My subscription
            Route::group(['prefix' => 'subscription'], function () {
                Route::get('/', [SubscriptionUserController::class, 'index'])->name('dashboard.subscription.index');
                Route::post('', [SubscriptionUserController::class, 'update'])->name('dashboard.subscription.update');
            });

            // User
            Route::get('profile', [DashboardProfileController::class, 'profile'])->name('dashboard.profile');
            Route::post('profile', [DashboardProfileController::class, 'updateProfile'])->name('dashboard.profile.update');
            Route::post('profile/password', [DashboardProfileController::class, 'updatePassword'])->name('dashboard.password.update');
            Route::post('profile/destroy', [DashboardProfileController::class, 'destroy'])->name('dashboard.profile.destroy');

        });

    }
);

