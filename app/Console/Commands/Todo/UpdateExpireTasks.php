<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Helpers\User\UserTaskHelper;
use Log;

class UpdateExpireTasks extends Command
{

    protected $signature = 'todo:update-expired';

    protected $description = '';

    public function handle()
    {

        $taskHelper = app(UserTaskHelper::class);

        // Update expired tasks
        $items = $taskHelper->updateExpireTasks();

        // Info message for console output
        $this->info('Expired tasks updated: ' . count($items));

        // Log the number of tasks created
        Log::info('Expired tasks updated: ' . count($items));

    }
}
