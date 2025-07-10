<?php

namespace App\Console\Commands\TranspGov;

use Illuminate\Console\Command;
use App\Repositories\User\UserCompanyRepo;
use App\Jobs\TranspGov\UpdateCompanySnapshots as SnapshotJob;

class UpdateCompanySnapshots extends Command {

    protected $signature = 'transgov:update-snapshots';

    protected $description = 'Update Company Snapshots from the Transport Government API';
    protected UserCompanyRepo $userCompanyRepo;

    public function __construct()
    {

        parent::__construct();

        $this->userCompanyRepo = new UserCompanyRepo();
    }

    public function handle()
    {

        $companies = $this->userCompanyRepo->getAll($filter=[], $paginate = 20, ['id' => 'asc']);
        $company_ids = $companies['Model']->pluck('dot_number', 'id')->toArray();

        $helper = app('App\Helpers\Company\CompanyIntegrationHelper');
        $helper->updateSnapshots($company_ids);

        // Company Snapshots
        SnapshotJob::dispatch($company_ids);

        $this->info("Transport Government company IDs have been added to the queue.");
    }

}