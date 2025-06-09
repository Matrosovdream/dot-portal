<?php

namespace App\Jobs\Saferweb;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\UserTaskHelper;
use Log;

class UpdateCompanyInspections implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 5;

    protected int $companyId;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function handle(UserTaskHelper $taskHelper): void
    {

        Log::info("Updating company inspections {$this->companyId} from Saferweb API");

    }

}
