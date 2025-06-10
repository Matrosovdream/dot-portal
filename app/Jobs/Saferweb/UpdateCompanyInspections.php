<?php

namespace App\Jobs\Saferweb;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\User\CompanyHelper;
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

    public function handle(CompanyHelper $companyHelper): void
    {

        $this->log("Updating company snapshot for company ID: {$this->companyId}");
     
        $res = $companyHelper->updateInspections(
            $this->companyId,
        );

        if ($res === null) {
            $this->log("No data Company snapshot found for company ID: {$this->companyId}");
        } else {
            $this->log("Company snapshot updated successfully for company ID: {$this->companyId}");
        }

    }

    private function log(string $message): void
    {
        Log::info($message);
    }

}
