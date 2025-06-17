<?php

namespace App\Jobs\Driver;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\DriverHelper;
use Log;

class DriverTodoTasks implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 5;

    public array $filter = [];

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }
    
    public function handle(DriverHelper $driverHelper): void
    {

        $res = $driverHelper->addTodoTasks(
            $this->filter,
        );

    }

    private function log(string $message): void
    {
        Log::info($message);
    }

}
