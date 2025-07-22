<?php

namespace App\Console\Commands\TranspGov;

class TranspGovHelper
{
    
    public function getAllCompanies($filter = [], $paginate = 10000, $orderBy = ['id' => 'asc'])
    {
        
        $companyRepo = app('App\Repositories\User\UserCompanyRepo');

        $companies = $companyRepo->getAll($filter, $paginate, $orderBy);

        $company_ids = $companies['Model']->pluck('dot_number', 'id')->toArray();

        return $company_ids;

    }

}