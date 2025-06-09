<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mixins\Integrations\SaferwebAPI;
use App\Jobs\Saferweb\UpdateCompanySnapshot;
use App\Jobs\Saferweb\UpdateCompanyInspections;
use App\Repositories\User\UserRepo;


class UpdateSaferwebData extends Command
{
    protected $signature = 'saferweb:update {--chunk=100} {--delay=2}';

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

        $users = $this->userRepo->getAll([], $paginate = 1000);

        foreach ($users['items'] as $user) {

            // Skip users without a company 
            if( $user['company'] == null ) { continue; }

            // Company Snapshot
            UpdateCompanySnapshot::dispatch($user['company']['id'])
                ->delay(now()->addSeconds($delay));

            // Company Inspections
            UpdateCompanyInspections::dispatch($user['company']['id'])
                ->delay(now()->addSeconds($delay));    

        }

        $this->info("SaferWeb user updates completed.");
    }
}
