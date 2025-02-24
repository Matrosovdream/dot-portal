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
            ['name' => 'Basic', 'price' => 155],
        ];
        
        foreach ($subs as $item) {
            Subscription::firstOrCreate($item);
        }

    }
}
