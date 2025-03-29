<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefVehicleOwnershipType;

class RefVehicleOwnershipTypeSeeder extends Seeder
{

    public function run()
    {
        $roles = [
            ['name' => 'Own', 'code' => 'own'],
            ['name' => 'Leasing', 'code' => 'leasing'],
        ];

        foreach ($roles as $role) {

            RefVehicleOwnershipType::updateOrCreate(
                ['code' => $role['code']],
                ['name' => $role['name']]
            );

        }
    }

}
