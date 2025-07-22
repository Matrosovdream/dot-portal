<?php

namespace App\Console\Commands\TranspGov;

use Illuminate\Console\Command;
use App\Console\Commands\TranspGov\TranspGovHelper;
use App\Jobs\TranspGov\UpdateCompanyInspections as InspectionJob;

class UpdateCompanyInspections extends Command {

    protected $signature = 'transgov:update-inspections {--chunk=50}';

    protected $description = 'Update Company Inspections from the Transport Government API';
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

        foreach ($company_ids as $chunk) {

            // Dispatch the job for each chunk
            InspectionJob::dispatch($chunk)->delay(now()->addSeconds($this->delayChunks));

            $this->info("Transport Government company inspections have been added to the queue: " . implode(', ', $chunk));
        }

        
    }

}