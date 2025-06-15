<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\DashboardUsersController;
use App\Http\Controllers\Dashboard\DashboardSettingsController;
use App\Http\Controllers\Dashboard\DashboardGatewaysController;
use App\Http\Controllers\Dashboard\ProfileCompanyController;
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
use App\Http\Controllers\Dashboard\VehicleUserController;
use App\Http\Controllers\Dashboard\InsuranceVehicleController;
use App\Http\Controllers\Dashboard\ToDoController;
use App\Http\Controllers\Dashboard\SearchController;

Route::group([
    'prefix' => 'dashboard',
    'as' => 'dashboard.',
    'middleware' => ['auth']
], function () {

    // Home dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Search
    Route::post('search', [SearchController::class, 'globalSearchAjax'])->name('search.global.ajax');
    Route::get('search', [SearchController::class, 'globalSearchAjax'])->name('search.global.ajax');

    // Notifications
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications');

    // To-Do list
    Route::prefix('todo')->name('todo.')->group(function () {
        Route::get('/', [ToDoController::class, 'index'])->name('index');
        Route::get('{task_id}', [ToDoController::class, 'show'])->name('show');
    });

    // Drivers
    Route::prefix('my-drivers')->name('drivers.')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('terminated', [DriverController::class, 'terminated'])->name('terminated');
        Route::get('create', [DriverController::class, 'create'])->name('create');
        Route::post('/', [DriverController::class, 'store'])->name('store');
        Route::get('{driver}', [DriverController::class, 'show'])->name('show');
        Route::post('{driver}', [DriverController::class, 'update'])->name('update');
        Route::post('{driver}', [DriverController::class, 'terminateDriver'])->name('terminate');
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
            Route::get('drugtest', [DriverController::class, 'drugtest'])->name('drugtest');
            Route::post('drugtest', [DriverController::class, 'updateDrugtest'])->name('drugtest.update');
            Route::get('mvr', [DriverController::class, 'mvr'])->name('mvr');
            Route::post('mvr', [DriverController::class, 'updateMvr'])->name('mvr.update');
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
            Route::post('{service}/update-status', [ServiceAdminController::class, 'updateServiceStatus'])->name('updatestatus');

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
            Route::post('{request_id}/updatestatus', [RequestAdminController::class, 'updateStatus'])->name('updatestatus');
            Route::post('{request_id}/updatefields', [RequestAdminController::class, 'updateFields'])->name('updatefields');
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
    Route::middleware(['hasRole:driver,company'])->group(function () {

        // Subscription
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/', [SubscriptionUserController::class, 'index'])->name('index');
            Route::post('/', [SubscriptionUserController::class, 'updateSubscription'])->name('update');
            Route::post('cancel', [SubscriptionUserController::class, 'cancelSubscription'])->name('cancel');

            // Cards
            //Route::get('cards', [SubscriptionUserController::class, 'cards'])->name('cards');
            Route::post('cards', [SubscriptionUserController::class, 'storeCard'])->name('cards.store');
            Route::post('cards/{card_id}/delete', [SubscriptionUserController::class, 'destroyCard'])->name('cards.destroy');
            Route::get('cards/{card_id}', [SubscriptionUserController::class, 'showCard'])->name('cards.show');
            Route::post('cards/{card_id}', [SubscriptionUserController::class, 'updateCard'])->name('cards.update');
            // makeprimary
            Route::post('cards/{card_id}', [SubscriptionUserController::class, 'makePrimaryCard'])->name('cards.makeprimary');
        });

        // Profile routes
        Route::prefix('profile')->name('profile.')->group(function () {

            // Company profile routes
            Route::get('/', [ProfileCompanyController::class, 'profilePreview'])->name('show');
            Route::post('/', [ProfileCompanyController::class, 'updateProfile'])->name('update');
            Route::post('password', [ProfileCompanyController::class, 'updatePassword'])->name('password.update');
            Route::get('edit', [ProfileCompanyController::class, 'profileEdit'])->name('edit');
            Route::post('edit', [ProfileCompanyController::class, 'profileUpdate'])->name('update');
            Route::post('address', [ProfileCompanyController::class, 'profileAddressUpdate'])->name('address.update');
            Route::get('company', [ProfileCompanyController::class, 'companyEdit'])->name('company.edit');
            Route::post('company', [ProfileCompanyController::class, 'companyUpdate'])->name('company.update');

            // Driver routes
            Route::get('/driverlicense', [ProfileCompanyController::class, 'driverLicense'])->name('driverlicense.edit');
            Route::post('/driverlicense', [ProfileCompanyController::class, 'updateDriverLicense'])->name('driverlicense.update');
            Route::get('/medicalcard', [ProfileCompanyController::class, 'medicalCard'])->name('medicalcard.edit');
            Route::post('/medicalcard', [ProfileCompanyController::class, 'updateMedicalCard'])->name('medicalcard.update');
        
        });
    });

    // Service requests
    Route::prefix('service-request')->name('servicerequest.')->group(function () {
        Route::get('history', [RequestUserController::class, 'history'])->name('history.index');
        Route::get('history/{request_id}', [RequestUserController::class, 'historyShow'])->name('history.show');
        Route::get('history/{request_id}/payments', [RequestUserController::class, 'historyShowPayments'])->name('history.show.payments');
        Route::get('history/{request_id}/pay', [RequestUserController::class, 'historyShowPay'])->name('history.showpay');
        Route::post('history/{request_id}/pay', [RequestUserController::class, 'historyShowPayProcess'])->name('history.showpayprocess');

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
        Route::get('/', [DocumentsController::class, 'index'])->name('index');
    });

    // Vehicles
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [VehicleUserController::class, 'index'])->name('index');
        Route::get('create', [VehicleUserController::class, 'create'])->name('create');
        Route::post('/', [VehicleUserController::class, 'store'])->name('store');
        Route::get('{vehicle_id}', [VehicleUserController::class, 'show'])->name('show');
        Route::post('{vehicle_id}', [VehicleUserController::class, 'update'])->name('update');
        Route::delete('{vehicle_id}', [VehicleUserController::class, 'destroy'])->name('destroy');

        Route::prefix('{vehicle_id}')->name('show.')->group(function () {
            // Profile
            Route::get('profile', [VehicleUserController::class, 'profile'])->name('profile');
            Route::post('profile', [VehicleUserController::class, 'updateProfile'])->name('profile.update');
            // MVR
            //Route::get('mvr', [VehicleUserController::class, 'mvr'])->name('mvr');
            //Route::post('mvr', [VehicleUserController::class, 'updateMvr'])->name('mvr.update');
            // Insurance
            Route::get('insurance', [VehicleUserController::class, 'insurance'])->name('insurance');
            Route::post('insurance', [VehicleUserController::class, 'updateInsurance'])->name('insurance.update');
            // Inspections
            Route::get('inspections', [VehicleUserController::class, 'inspections'])->name('inspections');
            //Route::post('inspections', [VehicleUserController::class, 'storeInspection'])->name('inspections.store');
            //Route::post('inspections/{inspection_id}', [VehicleUserController::class, 'updateInspection'])->name('inspections.update');
            //Route::delete('inspections/{inspection_id}', [VehicleUserController::class, 'destroyInspection'])->name('inspections.destroy');

            // Crashes
            Route::get('crashes', [VehicleUserController::class, 'crashes'])->name('crashes');

            // Driver history
            Route::get('driver-history', [VehicleUserController::class, 'driverHistory'])->name('driverhistory');
            Route::post('driver-history', [VehicleUserController::class, 'storeDriverHistory'])->name('driverhistory.store');
            Route::post('driver-history/{drh_id}', [VehicleUserController::class, 'updateDriverHistory'])->name('driverhistory.update');
            Route::delete('driver-history/{drh_id}', [VehicleUserController::class, 'destroyDriverHistory'])->name('driverhistory.destroy');

            Route::get('logs', [VehicleUserController::class, 'logs'])->name('logs');
        });

    });

    // Insurance vehicles
    Route::prefix('insurance-vehicles')->name('insurance-vehicles.')->group(function () {
        Route::get('/', [InsuranceVehicleController::class, 'index'])->name('index');
        Route::get('create', [InsuranceVehicleController::class, 'create'])->name('create');
        Route::post('/', [InsuranceVehicleController::class, 'store'])->name('store');
        Route::get('{insurance_id}', [InsuranceVehicleController::class, 'show'])->name('show');
        Route::post('{insurance_id}', [InsuranceVehicleController::class, 'update'])->name('update');
        Route::delete('{insurance_id}', [InsuranceVehicleController::class, 'destroy'])->name('destroy');

        Route::prefix('{insurance_id}')->name('show.')->group(function () {
            Route::get('profile', [InsuranceVehicleController::class, 'profile'])->name('profile');
            Route::post('profile', [InsuranceVehicleController::class, 'updateProfile'])->name('profile.update');
        });
    });

});
