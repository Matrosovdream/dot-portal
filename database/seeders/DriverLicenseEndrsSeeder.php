<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefDriverLicenseEndrs;

class DriverLicenseEndrsSeeder extends Seeder
{

    public function run()
    {
        $items = [
            ['title' => 'H', 'slug' => 'h', 'order' => 1, 'is_published' => true],
            ['title' => 'N', 'slug' => 'n', 'order' => 2, 'is_published' => true],
            ['title' => 'P', 'slug' => 'p', 'order' => 3, 'is_published' => true],
            ['title' => 'S', 'slug' => 's', 'order' => 4, 'is_published' => true],
            ['title' => 'T', 'slug' => 't', 'order' => 5, 'is_published' => true],
            ['title' => 'X', 'slug' => 'x', 'order' => 6, 'is_published' => true],
        ];

        foreach ($items as $item) {
            RefDriverLicenseEndrs::firstOrCreate( $item );
        }
    }

}
