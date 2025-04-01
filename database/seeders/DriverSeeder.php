<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $drivers = [];

        $drivers[] = [
            "main" => [
                "firstname" => "John",
                "lastname" => "Doe",
                "phone" => "1234567890",
                "email" => "test@gmail.com",
                "dob" => "2021-01-01",
                "ssn" => "123456789",
                "hire_date" => "2021-01-01",
                "driver_type_id" => 1,
                "user_id" => 3,
                "company_id" => 3,
            ],
            "address" => [
                "address1" => "123 Main St",
                "address2" => "Apt 1",
                "city" => "New York",
                "state_id" => 1,
                "zip" => "10001"
            ],
            "license" => [
                "type_id" => 1,
                "endorsement_id" => 1,
                "license_number" => "123456789",
                "expiration_date" => "2023-01-01",
                "state_id" => 1,
            ],
            "medical" => [
                "examiner_name" => "Dr. Smith",
                "national_registry" => "123456789",
                "issue_date" => "2021-01-01",
                "expiration_date" => "2023-01-01",
            ],
        ];

        $drivers[] = [
            "main" => [
                "firstname" => "Stan",
                "lastname" => "Matrosov",
                "phone" => "+38111222",
                "email" => "matrosovo@gmail.com",
                "dob" => "2017-03-09",
                "ssn" => "888899922",
                "hire_date" => "2024-01-01",
                "driver_type_id" => 1,
                "user_id" => 3,
                'company_id' => 3,
            ],
            "address" => [
                "address1" => "123 Main St",
                "address2" => "Apt 1",
                "city" => "New York",
                "state_id" => 1,
                "zip" => "10001"
            ],
            "license" => [
                "type_id" => 1,
                "endorsement_id" => 1,
                "license_number" => "123456789",
                "expiration_date" => "2023-01-01",
                "state_id" => 1,
            ],
            "medical" => [
                "examiner_name" => "Dr. Smith",
                "national_registry" => "123456789",
                "issue_date" => "2021-01-01",
                "expiration_date" => "2023-01-01",
            ],
        ];

        // Add driver
        foreach ($drivers as $driverSet) {
            // Create driver
            $driver = Driver::create($driverSet['main']);

            // Create license
            $driver->address()->create($driverSet['address']);

            // Create license
            $driver->license()->create($driverSet['license']);

            // Create medical card
            $driver->medicalCard()->create($driverSet['medical']);

        }

    }
}
