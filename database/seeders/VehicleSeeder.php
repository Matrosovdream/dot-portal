<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ServiceField;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $vehicles = [
            [
                'unit_type_id' => 1,
                'number' => '123456',
                'vin' => '1234567890',
                'ownership_type_id' => 1,
                'driver_id' => 1,
                'company_id' => 1,
                'reg_expire_date' => '2022-01-01',
                'inspection_expire_date' => '2022-01-01',
                'mvr' => [
                    'mvr_number' => 'MVR123456',
                    'mvr_date' => '2022-01-01',
                ],
            ],
            [
                'unit_type_id' => 2,
                'number' => '654321',
                'vin' => '0987654321',
                'ownership_type_id' => 2,
                'driver_id' => 2,
                'company_id' => 1,
                'reg_expire_date' => '2022-01-01',
                'inspection_expire_date' => '2022-01-01',
                'mvr' => [
                    'mvr_number' => 'MVR654321',
                    'mvr_date' => '2022-01-01',
                ],
            ],
        ];

        // Update or create by number
        foreach ($vehicles as $vehicleSet) {

            $mvr = $vehicleSet['mvr'] ?? null;
            unset($vehicleSet['mvr']);

            $vehicle = Vehicle::updateOrCreate(
                ['number' => $vehicleSet['number']],
                $vehicleSet
            );

            // Create or update MVR
            if (isset($mvr)) {
                $vehicle->mvr()->updateOrCreate(
                    ['vehicle_id' => $vehicle->id],
                    $mvr
                );
            }

        }

    }
}
