<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefCountryStates;

class CountryStateSeeder extends Seeder
{

    public function run(): void
    {
        
        // Retrieve the countries from the JSON file
        $list = json_decode(file_get_contents(database_path('references/us_states.json')), true);

        // Insert the countries into the database
        foreach ($list as $language) {

            RefCountryStates::firstOrCreate([
                'name' => $language['name'],
                'code' => $language['code'],
            ]);

        }    

    }
}
