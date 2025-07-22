<?php

namespace App\Console\Commands\TranspGov;

use Illuminate\Console\Command;
use App\Console\Commands\TranspGov\TranspGovHelper;
use App\Jobs\TranspGov\UpdateCompanyCrashes as CrashJob;

class UpdateCompanyCrashes extends Command {

    protected $signature = 'transgov:update-crashes {--chunk=50}';

    protected $description = 'Update Company Crashes from the Transport Government API';
    protected $delayChunks = 1; // Delay between chunks in seconds, can be adjusted as needed
    protected TranspGovHelper $helper;

    public function __construct()
    {

        parent::__construct();

        $this->helper = new TranspGovHelper();
    }

    public function handle()
    {

        $chunkSize = (int)$this->option('chunk');

        $company_ids = $this->helper->getAllCompanies();

        // Chunk the company IDs to avoid memory issues
        $company_ids = array_chunk($company_ids, $chunkSize, true);
        $this->delayChunks = 100;
        foreach ($company_ids as $chunk) {

            // Dispatch the job for each chunk
            CrashJob::dispatch($chunk)->delay(now()->addSeconds($this->delayChunks));

            //$helper = app('App\Helpers\Company\CompanyIntegrationHelper');
            //$helper->updateCrashes($chunk);
            //dd(1);

            $this->info("Transport Government company crashes have been added to the queue: " . implode(', ', $chunk));
        }

        
    }

}