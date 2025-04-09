<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $subs = [
            [
                'name' => '5 Drivers', 
                'price' => 59.99,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a small company',
                'description' => '5 drivers for 59.99$ per month',
                'drivers_amount' => 5,
                'points' => [
                    [
                        'title' => 'Up to 5 Active Drivers',
                        'included' => 1,
                    ],
                    [
                        'title' => 'Request section for free',
                        'included' => 1,
                    ],
                ],
            ],
            [
                'name' => '10 Drivers', 
                'price' => 99.99,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a middle company',
                'description' => '10 drivers for 99.99$ per month',
                'drivers_amount' => 10,
                'points' => [
                    [
                        'title' => 'Up to 10 Active Drivers',
                        'included' => 1,
                    ],
                    [
                        'title' => 'Request section for free',
                        'included' => 1,
                    ],
                ],
            ],
            [
                'name' => '20 Drivers', 
                'price' => 199.99,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a middle+ company',
                'description' => '20 drivers for 199.99$ per month',
                'drivers_amount' => 20,
                'points' => [
                    [
                        'title' => 'Up to 20 Active Drivers',
                        'included' => 1,
                    ],
                    [
                        'title' => 'Request section for free',
                        'included' => 1,
                    ],
                ],
            ],
        ];
        
        foreach ($subs as $item) {

            $points = $item['points'];
            unset($item['points']);

            $sub = Subscription::firstOrCreate($item);

            // Add points
            foreach ($points as $point) {
                $sub->points()->firstOrCreate($point);
            }

        }

    }
}
