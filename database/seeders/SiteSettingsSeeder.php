<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSettings;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings =  [
            ['key' => 'sitename', 'value' => 'Visa App!'],
            ['key' => 'email', 'value' => 'e.visa@gmail.com'],
            ['key' => 'phone', 'value' => '+91590 088 55'],
            ['key' => 'address', 'value' => '456 Elm Avenue Springfield, IL 62701'],
            ['key' => 'work_time', 'value' => 'Monday - Friday 09:00 am - 05:00 Pm'],
            ['key' => 'copyright_text', 'value' => 'Copyright © 2023 e.visa All rights reserved.'],
        ];

        foreach ($settings as $setting) {
            SiteSettings::updateOrCreate(
                ['key' => $setting['key']], 
                [
                    'key' => $setting['key'],
                    'value' => $setting['value']
                ]
            );
        }

    }
}
