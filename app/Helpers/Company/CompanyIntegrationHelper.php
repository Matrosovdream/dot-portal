<?php

namespace App\Helpers\Company;

use App\Helpers\TranspGov\TranspGovSnapshot;
use App\Repositories\User\CompanySaferwebRepo;

class CompanyIntegrationHelper {

    public function updateSnapshots(array $companies): array
    {

        if (empty($companies)) {
            return [];
        }

        // Reverse $companies key and value
        $companiesRev = array_flip($companies);
        
        // Init classes
        $companySaferwebRepo = app(CompanySaferwebRepo::class);
        $companyModel = app('App\Models\UserCompany');

        $transportGov = app(TranspGovSnapshot::class);
        $transportGov->mapWithModel = true; // Enable mapping to model

        $companiesDataRaw = $companyModel->whereIn('id', $companiesRev)->get();
        $companiesData = [];
        foreach ($companiesDataRaw as $company) {
            $companiesData[ $company->id ] = $company->toArray();
        }

        // Retrieve results from the Transport Government API
        $items = $transportGov->getItemsByDot(
            $companies,
            1000
        );

        foreach ($items as $item) {

            $companyId = $companiesRev[ $item['dot_number'] ] ?? null;

            if ( $companyId ) {
                $mappedData = $item;
                $mappedData['user_id'] = $companiesData[ $companyId ]['user_id'] ?? null;

                // Sync the data with the repository
                $companySaferwebRepo->sync($companyId, $mappedData);
            }
        }

        return [];

    }

}