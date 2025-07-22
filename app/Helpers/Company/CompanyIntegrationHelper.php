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

    public function updateInspections(array $companies): bool
    {

        if (empty($companies)) {
            return false;
        }

        // Init classes
        $saferwebRepo = app(VehicleInspectionsSaferwebRepo::class);

        $transportGov = app(TranspGovInspection::class);
        $transportGov->mapWithModel = true; // Enable mapping to model

        // Retrieve results from the Transport Government API
        $items = $transportGov->getItemsByDot(
            $companies,
            100000
        );

        // let's chunk items to avoid memory issues
        $itemChunks = array_chunk($items, 100, true);

        foreach($itemChunks as $chunk) {

            // Sync items with the repository
            $saferwebRepo->syncItems($chunk);

        }

        return true;

    }

    public function updateCrashes(array $companies): bool
    {

        if (empty($companies)) {
            return false;
        }

        // Init classes
        $saferwebRepo = app(VehicleCrashesSaferwebRepo::class);

        $transportGov = app(TranspGovCrash::class);
        $transportGov->mapWithModel = true; // Enable mapping to model

        // Retrieve results from the Transport Government API
        $items = $transportGov->getItemsByDot(
            $companies,
            100000
        );

        // let's chunk items to avoid memory issues
        $itemChunks = array_chunk($items, 100, true);

        foreach($itemChunks as $chunk) {

            // Sync items with the repository
            $saferwebRepo->syncItemsUpsert($chunk);

        }

        return true;

    }

}