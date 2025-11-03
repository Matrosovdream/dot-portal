<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefPaymentMethod;

class RefPaymentMethodSeeder extends Seeder
{

    public function run()
    {
        $items = [
            [
                'name' => 'Authorize.Net',
                'code' => 'authorize_net',
                'description' => 'Payment via Authorize.Net gateway.',
            ],
        ];

        foreach ($items as $item) {
            RefPaymentMethod::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

    }

}
