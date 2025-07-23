<?php

namespace App\Helpers\Company;

use App\Helpers\TranspGov\TranspGovSnapshot;
use App\Helpers\TranspGov\TranspGovInspection;
use App\Helpers\TranspGov\TranspGovCrash;
use App\Repositories\Vehicle\VehicleInspectionsSaferwebRepo;
use App\Repositories\Vehicle\VehicleCrashesSaferwebRepo;
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

        // Retrieve company data
        $companiesData = $this->getCompanies($companiesRev);

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

    public function updateInspections(array $companies)
    {
        return $this->updateMultipleEntity(
            $companies,
            VehicleInspectionsSaferwebRepo::class,
            TranspGovInspection::class,
            'syncItems',
            ['unique_id']
        );
    }

    public function updateCrashes(array $companies): bool
    {
        return $this->updateMultipleEntity(
            $companies,
            VehicleCrashesSaferwebRepo::class,
            TranspGovCrash::class,
            'syncItemsUpsert',
            ['report_number']
        );
    }

    private function updateMultipleEntity(
        array $companies,
        string $repoClass,
        string $apiClass,
        string $syncMethod,
        array $uniqueConstraints = []
    ): bool|array {

        if (empty($companies)) {
            return false;
        }

        // Init classes
        $repo = app($repoClass);
        $api = app($apiClass);
        $api->mapWithModel = true;

        // Fetch items from API
        $items = $api->getItemsByDot($companies, 100000);
        $itemChunks = array_chunk($items, 100, true);

        foreach ($itemChunks as $chunk) {
            $repo->$syncMethod($chunk, $uniqueConstraints);
        }

        // Update Scout
        $repo->model->query()->searchable();

        return is_a($api, TranspGovCrash::class) ? true : $items;
    }


    protected function getCompanies( array $companies ): array {

        $companyModel = app('App\Models\UserCompany');

        $companiesDataRaw = $companyModel->whereIn('id', $companies)->get();
        $companiesData = [];
        foreach ($companiesDataRaw as $company) {
            $companiesData[ $company->id ] = $company->toArray();
        }

        return $companiesData;

    }

}