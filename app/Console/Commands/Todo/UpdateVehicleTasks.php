<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Helpers\User\UserTaskHelper;

class UpdateVehicleTasks extends Command
{

    protected $signature = 'todo:update-vehicles';

    protected $description = '';

    public function handle()
    {

        $taskHelper = app(UserTaskHelper::class);

        // Update driver tasks
        $items = $taskHelper->updateVehicleTasks();

        // Log the number of tasks created
        $this->info('Vehicle tasks updated: ' . count($items));

    }
}
