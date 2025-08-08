<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefVehicleUnitType;

class RefVehicleUnitTypeSeeder extends Seeder
{

    public function run()
    {

        $roles = [
            ['name' => 'Under 10,001 lbs', 'code' => 'big'],
            ['name' => '10,001–26,000 lbs', 'code' => 'medium'],
            ['name' => '26,001 lbs or more', 'code' => 'small'],
        ];

        foreach ($roles as $role) {
            // Create or update by code
            RefVehicleUnitType::updateOrCreate(
                ['code' => $role['code']],
                ['name' => $role['name']]
            );
        }
    }

}
