<?php
namespace App\References;

class RequestReferences {

    public function getRequestStatuses() {

        return [
            ['name' => 'Processing', 'slug' => 'processing', 'color' => 'blue', 'published' => 1],
            ['name' => 'Waiting for payment', 'slug' => 'waiting-payment', 'color' => 'blue', 'published' => 1],
            ['name' => 'On hold', 'slug' => 'hold', 'color' => 'gray', 'published' => 1],
            ['name' => 'Pending verification', 'slug' => 'pending', 'color' => 'gray', 'published' => 1],
            ['name' => 'Submitted', 'slug' => 'submitted', 'color' => 'gray', 'published' => 1],
            ['name' => 'Completed', 'slug' => 'completed', 'color' => 'green', 'published' => 1],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => 'red', 'published' => 1],
        ];
        
    }

}