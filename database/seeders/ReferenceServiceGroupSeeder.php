<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefServiceGroup;

class ReferenceServiceGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $groups = [
            [
                'name' => 'Taxes & Permits',
                'slug' => 'taxes-permits',
                'description' => '',
                'is_active' => 1
            ],
            [
                'name' => 'Safety Compliance',
                'slug' => 'safety-compliance',
                'description' => '',
                'is_active' => 1
            ],
        ];
        
        foreach ($groups as $item) {

            RefServiceGroup::firstOrCreate(
                ['slug' => $item['slug']
            ], $item);
        }

    }
}
