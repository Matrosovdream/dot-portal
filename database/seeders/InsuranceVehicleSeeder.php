<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceVehicle;
use App\Repositories\Driver\DriverRepo;

class InsuranceVehicleSeeder extends Seeder
{
    
    protected $insuranceRepo;

    public function __construct()
    {
        $this->insuranceRepo = new InsuranceVehicle();
    }

    public function run(): void
    {

        $items = [
            [
                "main" => [
                    "name" => "Urgent insurance",
                    "number" => "123456789",
                    "start_date" => "2021-01-01",
                    "end_date" => "2023-01-01",
                    "file_id" => null,
                    "company_id" => 3,
                    "user_id" => null,
                ],
            ],
            [
                "main" => [
                    "name" => "basic insurance",
                    "number" => "987654321",
                    "start_date" => "2022-01-01",
                    "end_date" => "2024-01-01",
                    "file_id" => null,
                    "company_id" => 3,
                    "user_id" => null,
                ],
            ],
        ];

        // Add insurance
        foreach ($items as $item) {

            $existingInsurance = $this->insuranceRepo->where('number', $item['main']['number'])->first();

            if ($existingInsurance) {
                $existingInsurance->update($item['main']);
            } else {
                $this->insuranceRepo->create($item['main']);
            }

        }

    }
}
