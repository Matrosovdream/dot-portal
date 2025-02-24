<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSubscription;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $subs = [
            ['user_id' => 1, 'subscription_id' => 1, 'price' => 155, 'discount' => 0, 'start_date' => '2021-01-01', 'end_date' => '2021-12-31', 'status' => 'active'],
            ['user_id' => 2, 'subscription_id' => 1, 'price' => 155, 'discount' => 0, 'start_date' => '2021-01-01', 'end_date' => '2021-12-31', 'status' => 'active'],
            ['user_id' => 3, 'subscription_id' => 1, 'price' => 155, 'discount' => 0, 'start_date' => '2021-01-01', 'end_date' => '2021-12-31', 'status' => 'active'],
        ];
        
        foreach ($subs as $item) {
            UserSubscription::firstOrCreate($item);
        }

    }
}
