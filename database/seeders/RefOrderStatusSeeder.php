<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefOrderStatus;

class RefOrderStatusSeeder extends Seeder
{

    public function run()
    {
        $items = [
            [
                'name' => 'Pending',
                'code' => 'pending',
                'description' => 'Order is pending and awaiting processing.',
            ],
            [
                'name' => 'Processing',
                'code' => 'processing',
                'description' => 'Order is being processed.',
            ],
            [
                'name' => 'Completed',
                'code' => 'completed',
                'description' => 'Order has been completed successfully.',
            ],
            [
                'name' => 'Failed',
                'code' => 'failed',
                'description' => 'Order processing has failed.',
            ],
            [
                'name' => 'Cancelled',
                'code' => 'cancelled',
                'description' => 'Order has been cancelled.',
            ],
        ];  

        foreach ($items as $item) {
            RefOrderStatus::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

    }

}
