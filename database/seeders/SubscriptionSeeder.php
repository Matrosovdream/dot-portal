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
                'name' => '1-5 Drivers ($30 per driver)', 
                'slug' => '1-5-drivers',
                'price_per_driver' => 30.00,
                'drivers_amount_from' => 1,
                'drivers_amount_to' => 5,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a small company',
                'description' => '1-5 drivers for $30 per driver',
                'points' => [
                    [ 'title' => 'Up to 5 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '6-10 Drivers ($25 per driver)',
                'slug' => '6-10-drivers',
                'price_per_driver' => 25.00,
                'drivers_amount_from' => 6,
                'drivers_amount_to' => 10,
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a medium company',
                'description' => '6-10 drivers for $25 per driver',
                'points' => [
                    [ 'title' => 'Up to 10 Active Drivers', 'included' => 1 ],
                    [ 'title' => 'Request section for free', 'included' => 1 ]
                ]
            ],
            [
                'name' => '11-100 Drivers ($20 per driver)',
                'slug' => '11-100-drivers',
                'price_per_driver' => 20.00,
                'drivers_amount_from' => 11,
                'drivers_amount_to' => 100, 
                'discount' => 0,
                'duration' => 'monthly',
                'short_description' => 'Best for a large company',
                'description' => '11+ drivers for $20 per driver',
                'points' => [
                    [ 'title' => 'Unlimited Active Drivers', 'included' => 1 ],
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
                    [ 'title' => 'Custom Active Drivers', 'included' => 1 ],
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
