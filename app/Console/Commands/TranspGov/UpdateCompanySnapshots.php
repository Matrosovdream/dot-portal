<?php

namespace App\Console\Commands\TranspGov;

use Illuminate\Console\Command;
use App\Repositories\User\UserCompanyRepo;
use App\Jobs\TranspGov\UpdateCompanySnapshots as SnapshotJob;

class UpdateCompanySnapshots extends Command {

    protected $signature = 'transgov:update-snapshots {--chunk=50}';

    protected $description = 'Update Company Snapshots from the Transport Government API';
    protected $delayChunks = 1; // Delay between chunks in seconds, can be adjusted as needed
    protected UserCompanyRepo $userCompanyRepo;

    public function __construct()
    {

        parent::__construct();

        $this->userCompanyRepo = new UserCompanyRepo();
    }

    public function handle()
    {

        $chunkSize = (int)$this->option('chunk');

        $companies = $this->userCompanyRepo->getAll($filter=[], $paginate = 100, ['id' => 'asc']);
        $company_ids = $companies['Model']->pluck('dot_number', 'id')->toArray();

        // Chunk the company IDs to avoid memory issues
        $company_ids = array_chunk($company_ids, $chunkSize, true);

        foreach ($company_ids as $chunk) {

            //$helper = app('App\Helpers\Company\CompanyIntegrationHelper');
            //$helper->updateSnapshots($chunk);

            // Dispatch the job for each chunk
            SnapshotJob::dispatch($chunk)->delay(now()->addSeconds($this->delayChunks));

            $this->info("Transport Government company IDs have been added to the queue: " . implode(', ', $chunk));
        }

        
    }

}