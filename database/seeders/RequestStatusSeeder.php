<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefRequestStatus;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $statuses = [
            ['name' => 'Processing', 'slug' => 'processing', 'color' => 'blue', 'published' => 1],
            ['name' => 'Waiting for payment', 'slug' => 'waiting-payment', 'color' => 'blue', 'published' => 1],
            ['name' => 'On hold', 'slug' => 'hold', 'color' => 'gray', 'published' => 1],
            ['name' => 'Pending verification', 'slug' => 'pending', 'color' => 'gray', 'published' => 1],
            ['name' => 'Submitted', 'slug' => 'submitted', 'color' => 'gray', 'published' => 1],
            ['name' => 'Completed', 'slug' => 'completed', 'color' => 'green', 'published' => 1],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => 'red', 'published' => 1],
        ];
        
        foreach ($statuses as $status) {
            RefRequestStatus::firstOrCreate($status);
        }

    }
}
