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
            ['name' => 'Big', 'code' => 'big'],
            ['name' => 'Medium', 'code' => 'medium'],
            ['name' => 'Small', 'code' => 'small'],
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
