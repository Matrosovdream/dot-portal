<?php

namespace App\Console\Commands\Saferweb;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepo;

class UpdateSnapshots {

    protected $signature = 'saferweb:update-snapshots';

    protected $description = 'Update Company Snapshots from the Transport Government API';
    protected UserRepo $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
    }

    public function handle()
    {

        $users = $this->userRepo->getAll($filter=[], $paginate = 1000);

        foreach ($users['items'] as $user) {

            // Skip users without a company 
            if( $user['company'] == null ) { continue; }

            $companyId = $user['company']['id'];

            echo $companyId . "\n";

        }

        dd();

        $this->info("SaferWeb company IDs have been added to the queue.");
    }

}