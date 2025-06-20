<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Saferweb\UpdateCompanySnapshot;
use App\Jobs\Saferweb\UpdateCompanyInspections;
use App\Jobs\Saferweb\UpdateCompanyCrashes;
use App\Repositories\User\UserRepo;


class UpdateSaferwebData extends Command
{
    protected $signature = 'saferweb:update {user_id?} {--chunk=100} {--delay=2}';

    protected $description = 'Update users DOT info from SaferWeb API';
    protected UserRepo $userRepo;

    public function __construct()
    {
        parent::__construct();

        $this->userRepo = new UserRepo();
    }

    public function handle()
    {
        $chunkSize = (int)$this->option('chunk');
        $delay = (int)$this->option('delay');
        $userId = $this->argument('user_id') ?? null;

        $filter = [];
        if ($userId) {
            $filter['id'] = $userId;
        }

        $users = $this->userRepo->getAll($filter, $paginate = 1000);

        foreach ($users['items'] as $user) {

            // Skip users without a company 
            if( $user['company'] == null ) { continue; }

            $companyId = $user['company']['id'];

            // Company Snapshot
            UpdateCompanySnapshot::dispatch($companyId)
                ->delay(now()->addSeconds($delay));

            // Company Inspections
            UpdateCompanyInspections::dispatch($companyId)
                ->delay(now()->addSeconds($delay));   

            // Company Crashes
            UpdateCompanyCrashes::dispatch($companyId)
                ->delay(now()->addSeconds($delay));    

        }

        $this->info("SaferWeb company IDs have been added to the queue.");
    }
}
