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
            ['field_id' => 2, 'order' => 2],
            ['field_id' => 3, 'order' => 3],
            ['field_id'=> 4, 'order'=> 4],
        ];
        
        $services = [
            [
                'name' => 'MCS-150 Update', 
                'slug' => 'msc-150-update', 
                'description' => 'MCS-150 description', 
                'is_paid' => true,
                'price' => 50, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type' => 'custom',
                'form_id' => null,
            ],
            [
                'name' => 'UCR Renewal', 
                'slug' => 'ucr-renewel', 
                'description' => 'UCR Renewal description', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type'=> 'predefined',
                'form_id' => 1, // Assuming 1 is the ID for UCR form
            ],
            [
                'name' => 'MVR Check', 
                'slug' => 'mvr-check', 
                'description' => 'MVR check description', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 2,
                'form_type' => 'predefined',
                'form_id' => 9
            ],
        ];
        
        foreach ($services as $service) {
            $serviceData = Service::firstOrCreate($service);

            if( $service['form_type'] == 'custom' ) {

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
}
