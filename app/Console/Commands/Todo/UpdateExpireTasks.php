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

        // Messaging
        $text = 'Expired tasks updated: ' . count($items);
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
