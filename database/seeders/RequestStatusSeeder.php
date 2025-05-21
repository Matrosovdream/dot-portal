<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefRequestStatus;
use App\References\RequestReferences;

class RequestStatusSeeder extends Seeder
{

    public function __construct(
        private RequestReferences $requestReferences
    )
    {
        $this->requestReferences = new RequestReferences();
    }
    
    public function run(): void
    {
        
        $statuses = $this->requestReferences->getRequestStatuses();
        
        foreach ($statuses as $status) {
            RefRequestStatus::firstOrCreate($status);
        }

    }
}
