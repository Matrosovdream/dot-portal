<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $orderStatuses = [
            ['name' => 'Pending', 'slug' => 'pending', 'color' => 'gray', 'is_default' => 1],
            ['name' => 'Processing', 'slug' => 'processing', 'color' => 'blue', 'is_default' => 1],
            ['name' => 'Completed', 'slug' => 'completed', 'color' => 'green', 'is_default' => 1],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => 'red', 'is_default' => 1],
        ];
        
        foreach ($orderStatuses as $orderStatus) {
            OrderStatus::firstOrCreate($orderStatus);
        }

    }
}
