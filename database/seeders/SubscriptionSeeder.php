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
                'name' => '1-3 Drivers ($35 per driver)', 
                'slug' => '1-3-drivers',
                'price_per_driver' => 35.00,
                'drivers_amount_from' => 1,
                'drivers_amount_to' => 3,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a small company',
                'description' => '1-3 drivers for $35 per driver',
                'points' => [
                    [ 'title' => 'Up to 3 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '4-10 Drivers ($30 per driver)',
                'slug' => '4-10-drivers',
                'price_per_driver' => 30.00,
                'drivers_amount_from' => 4,
                'drivers_amount_to' => 10,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a medium company',
                'description' => '4-10 drivers for $30 per driver',
                'points' => [
                    [ 'title' => 'Up to 10 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '11-50 Drivers ($25 per driver)',
                'slug' => '11-50-drivers',
                'price_per_driver' => 25.00,
                'drivers_amount_from' => 11,
                'drivers_amount_to' => 50, 
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a large company',
                'description' => '11-50 drivers for $25 per driver',
                'points' => [
                    [ 'title' => 'Up to 50 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '50-99 Drivers ($20 per driver)',
                'slug' => '50-99-drivers',
                'price_per_driver' => 20.00,
                'drivers_amount_from' => 50,
                'drivers_amount_to' => 99,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a large company',
                'description' => '50-99 drivers for $20 per driver',
                'points' => [
                    [ 'title' => 'Up to 100 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '100+ Drivers (custom price)',
                'slug' => '100-plus-drivers',
                'price_per_driver' => 0.00,
                'is_custom_price' => true,
                'drivers_amount_from' => 101,
                'drivers_amount_to' => 100000,
                'short_description' => 'Custom subscription for your needs',
                'description' => 'Create a custom subscription based on your requirements',
                'points' => [
                    [ 'title' => 'Unlimited Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ]
        ];
        
        foreach ($subs as $item) {

            $points = $item['points'];
            unset($item['points']);

            $sub = Subscription::updateOrCreate(
                [
                    'slug' => $item['slug']
                ],
                $item
            );

            // Add points
            foreach ($points as $point) {
                $sub->points()->firstOrCreate($point);
            }

        }

    }
}
