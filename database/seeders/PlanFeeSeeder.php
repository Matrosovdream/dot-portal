<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanFee;

class PlanFeeSeeder extends Seeder
{

    public function run(): void
    {
        
        $items = [
            [
                'name' => 'Default Fee',
                'price' => 199,
            ]
        ];

        foreach ($items as $item) {
            PlanFee::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }

    }
}
