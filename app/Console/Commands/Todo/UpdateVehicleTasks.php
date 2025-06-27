<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Helpers\User\UserTaskHelper;
use Log;

class UpdateVehicleTasks extends Command
{

    protected $signature = 'todo:update-vehicles';

    protected $description = '';

    public function handle()
    {

        $taskHelper = app(UserTaskHelper::class);

        // Update driver tasks
        $items = $taskHelper->updateVehicleTasks();

        // Messaging
        $text = 'Vehicle tasks updated: ' . count($items);
        $this->log($text);

    }

    protected function log($text)
    {
        // Output the text to the console
        $this->info($text);

        // Log 
        Log::info($text);
    }

}
