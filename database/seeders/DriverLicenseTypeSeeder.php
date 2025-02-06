<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefDriverLicenseType;

class DriverLicenseTypeSeeder extends Seeder
{

    public function run()
    {
        $items = [
            ['title' => 'CDL A', 'slug' => 'cdl_a', 'order' => 1, 'is_published' => true],
            ['title' => 'CDL B', 'slug' => 'cdl_b', 'order' => 2, 'is_published' => true],
            ['title' => 'CDL C', 'slug' => 'cdl_c', 'order' => 3, 'is_published' => true],
            ['title' => 'Mexican', 'slug' => 'mexican', 'order' => 4, 'is_published' => true],
            ['title' => 'Non CDL', 'slug' => 'non_cdl', 'order' => 5, 'is_published' => true],
        ];

        foreach ($items as $item) {
            RefDriverLicenseType::firstOrCreate( $item );
        }
    }

}
