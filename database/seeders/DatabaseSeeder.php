<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            
            // Users
            RoleSeeder::class,
            UserSeeder::class,
            UserSubscriptionSeeder::class,
            UserPaymentCardSeeder::class,
            UserPaymentHistorySeeder::class,

            // Referecences
            LanguageSeeder::class,
            CurrencySeeder::class,
            RequestStatusSeeder::class,
            CountryStateSeeder::class,
            DriverLicenseEndrsSeeder::class,
            DriverLicenseTypeSeeder::class,
            DriverTypeSeeder::class,
            ReferenceFormFieldsSeeder::class,
            ReferenceServiceGroupSeeder::class,
            RefVehicleUnitTypeSeeder::class,
            RefVehicleOwnershipTypeSeeder::class,

            // Services
            ServiceSeeder::class,

            // Settings
            SiteSettingsSeeder::class,

            // Notifications
            NotificationSeeder::class,

            // Drivers
            DriverSeeder::class,

            // Subscriptions
            SubscriptionSeeder::class,
            
        ]);

    }
}
