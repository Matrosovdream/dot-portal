<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReferenceFormField;
use App\References\ServiceReferences;

class ReferenceFormFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $fieldsService = ServiceReferences::serviceFields();
        $fields = array_merge($fieldsService);
        
        foreach ($fields as $field) {

            ReferenceFormField::firstOrCreate(
                ['slug' => $field['slug'], 'entity' => $field['entity']
            ], $field);
        }

    }
}
