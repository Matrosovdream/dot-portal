<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Helpers\User\UserTaskHelper;
use Log;

class UpdateDriverTasks extends Command
{

    protected $signature = 'todo:update-drivers';

    protected $description = '';

    public function handle()
    {

        $taskHelper = app(UserTaskHelper::class);

        // Update driver tasks
        $items = $taskHelper->updateDriverTasks();

        // Log the number of tasks created
        Log::info('Driver tasks updated: ' . count($items));

    }
}
