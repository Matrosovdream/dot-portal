<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefDriverType;

class DriverTypeSeeder extends Seeder
{

    public function run()
    {
        $items = [
            ['title' => 'House', 'slug' => 'house', 'order' => 1, 'is_published' => true],
            ['title' => 'Owner operator', 'slug' => 'owner_operator', 'order' => 2, 'is_published' => true],
            ['title' => 'Third party', 'slug' => 'third_party', 'order' => 3, 'is_published' => true],
        ];

        foreach ($items as $item) {
            RefDriverType::firstOrCreate( $item );
        }
    }

}
