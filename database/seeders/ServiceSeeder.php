<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ServiceField;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $fields = [
            ['field_id' => 1, 'order' => 1],
            ['field_id' => 3, 'order' => 2],
            ['field_id' => 4, 'order' => 3],
            ['field_id' => 5, 'order' => 3],
        ];
        
        $services = [
            ['name' => 'MCS-150 Update', 'slug' => 'msc-150-update', 'description' => 'Service 1 description', 'price' => 50, 'status_id' => 1, 'group_id' => 1],
            ['name' => 'UCR Renewal', 'slug' => 'ucr-renewel', 'description' => 'Service 2 description', 'price' => 70, 'status_id' => 1, 'group_id' => 1],
            ['name' => 'MVR Check', 'slug' => 'mvr-check', 'description' => 'Service 3 description', 'price' => 90, 'status_id' => 1, 'group_id' => 2],
        ];
        
        foreach ($services as $service) {
            $serviceData = Service::firstOrCreate($service);
            
            // Add fields
            foreach ($fields as $field) {
                ServiceField::firstOrCreate([
                    'service_id' => $serviceData->id,
                    'field_id' => $field['field_id'],
                    'order' => $field['order'],
                ]);
            }
        }

    }
}
