<?php

namespace App\Jobs\TranspGov;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Company\CompanyIntegrationHelper as IntegrationHelper;
use Log;

class UpdateCompanySnapshots implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 5;

    public array $company_ids;

    public function __construct(array $company_ids)
    {
        $this->company_ids = $company_ids;
    }

    
    public function handle(IntegrationHelper $integrationHelper): void
    {

        $companiesStr = implode(', ', $this->company_ids);

        $this->log("Updating company snapshot for companies: { $companiesStr }");
     
        $res = $integrationHelper->updateSnapshots( $this->company_ids );

    }

    private function log(string $message): void
    {
        Log::info($message);
    }

}
