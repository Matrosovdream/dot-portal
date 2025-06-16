<?php

namespace App\Console\Commands\Driver;

use Illuminate\Console\Command;
use App\Jobs\Driver\UpdateCompanySnapshot;

class DriverTodoTasks extends Command
{

    protected $signature = 'driver:todo-tasks';

    protected $description = 'Reindex the search index for all searchable models - Scout package';

    public function handle()
    {

        // Dispatch the job to add todo tasks for drivers
        DriverTodoTasks::dispatch();

    }

}
