<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;
use App\Models\Driver; 
use App\Models\File;
use App\Models\Vehicle;
use App\Models\InsuranceVehicle;
use App\Models\VehicleCrashSaferweb;

class ReindexSearch extends Command
{

    protected $signature = 'app:reindex-search';

    protected $description = 'Reindex the search index for all searchable models - Scout package';

    public function handle()
    {
        
        $this->info('Reindexing search data started');

        // Searchable models
        foreach ($this->getModels() as $model) {
            $model::query()->searchable();
        }

        $this->info('Reindexing search data ended');

    }

    private function getModels()
    {
        return [
            Driver::class,
            File::class,
            Vehicle::class,
            InsuranceVehicle::class,
            VehicleCrashSaferweb::class,
        ];
    }

}
