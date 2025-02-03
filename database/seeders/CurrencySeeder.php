<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Retrieve the countries from the JSON file
        $list = json_decode(file_get_contents(database_path('references/currencies.json')), true);

        // Insert the countries into the database
        foreach ($list as $currency) {

            Currency::firstOrCreate([
                'name' => $currency['name'],
                'code' => $currency['code'],
                'symbol' => $currency['symbol'],
                'is_default' => $currency['is_default'],
            ]);
        }

    }
}
