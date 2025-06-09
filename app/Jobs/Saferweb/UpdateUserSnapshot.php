<?php

namespace App\Jobs\Saferweb;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\UserTaskHelper;
use Log;

class UpdateUserSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 5;

    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function handle(UserTaskHelper $taskHelper): void
    {

        Log::info("Updating user {$this->userId} from Saferweb API");
     
        /*
        $taskHelper->updateCompanySnapshot(
            $this->userId,
        );
        */

    }

}
