<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Request;

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

            // Referecences
            LanguageSeeder::class,
            CurrencySeeder::class,
            RequestStatusSeeder::class,
            CountryStateSeeder::class,

            // Services
            ServiceSeeder::class,

            // Settings
            SiteSettingsSeeder::class,

            // Notifications
            NotificationSeeder::class,
            
        ]);

    }
}
