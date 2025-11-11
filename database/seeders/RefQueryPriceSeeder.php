<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefQueryPrice;

class RefQueryPriceSeeder extends Seeder
{

    public function run()
    {

        $items = [];

        $items[] = [
            'type' => 'chm',
            'price_per_query' => 15.00,
            'rules' => [
                'driver_amount' => [
                    [
                        'value' => 10,
                        'rule' => '<',
                        'price' => 15.00
                    ],
                    [
                        'value' => 10,
                        'rule' => '=>',
                        'price' => 10.00
                    ],
                    [
                        'value' => 50,
                        'rule' => '=>',
                        'price' => 5.00
                    ],
                ]
            ],
        ];  

        foreach ($items as $item) {
            RefQueryPrice::updateOrCreate(
                ['type' => $item['type']],
                $item
            );
        }

    }

}
