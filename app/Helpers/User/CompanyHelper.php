<?php

namespace App\Helpers\User;

use App\Mixins\Integrations\SaferwebAPI;
use App\Repositories\User\UserCompanyRepo;
use App\Repositories\User\CompanySaferwebRepo;
use App\Repositories\Vehicle\VehicleRepo;

class CompanyHelper {

    public function updateSnapshot(int $company_id): array|null
    {

        $apiService = app(SaferwebAPI::class);
        $companyRepo = app(UserCompanyRepo::class);
        $companySaferwebRepo = app(CompanySaferwebRepo::class);

        $company = $companyRepo->getByID($company_id);
        $dotNumber = $company['dot_number'] ?? null;

        if ($dotNumber) {
            $apiData = $apiService->getCompanySnapshot($dotNumber);
        } else { return null; }

        if ( 
            empty($apiData['error']) &&
            $apiData != null
            ) { 

            $mappedData = [
                'user_id' => $company['user_id'] ?? null,
                'dot_number' => $apiData['usdot'] ?? null,
                'mc_number' => $apiData['mc_mx_ff_numbers'] ?? null,
                'legal_name' => $apiData['legal_name'] ?? null,
                'dba_name' => $apiData['dba_name'] ?? null,
                'entity_type' => $apiData['entity_type'] ?? null,
                'physical_address' => $apiData['physical_address'] ?? null,
                'mailing_address' => $apiData['mailing_address'] ?? null,
                'latest_update' => isset($apiData['latest_update']) ? \Carbon\Carbon::parse($apiData['latest_update'])->format('Y-m-d'): null,
                'api_data' => json_encode($apiData),
            ];

            $companySaferwebRepo->sync($company_id, $mappedData);

            return $apiData;

        } else {
            // Handle error or empty response
            return null;
        }
    }

    public function updateCrashes(int $company_id) {

        $apiService = app(SaferwebAPI::class);
        $companyRepo = app(UserCompanyRepo::class);
        $companySaferwebRepo = app(CompanySaferwebRepo::class);
        $vehicleRepo = app(VehicleRepo::class);

        $company = $companyRepo->getByID($company_id);
        $dotNumber = $company['dot_number'] ?? null;

        if ($dotNumber) {
            $apiData = $apiService->getCrashHistory($dotNumber);
        } else { return null; }

        if ( 
            empty($apiData['error']) &&
            $apiData != null
            ) { 

            $records = [];
            
            foreach ($apiData['crash_records'] as $record) {

                $vehicle = $vehicleRepo->getByVIN($record['vehicle']['vin'] ?? null);

                if ( !$vehicle ) { continue; }

                $mappedData = [
                    'vehicle_id' => $vehicle['id'],
                    'report_date' => isset($record['report_date']) ? \Carbon\Carbon::parse($record['report_date'])->format('Y-m-d'): null,
                    'report_number' => $record['report_number'] ?? null,
                    'report_sequence_number' => $record['report_sequence_number'] ?? null,
                    'report_state' => $record['report_state'] ?? null,
                    'report_state_id' => null,
                    'total_injuries' => $record['total_injuries'] ?? null,
                    'total_fatalities' => $record['total_fatalities'] ?? null,
                    'api_data' => json_encode($record),
                ];
                // Unique by report_number
                $records[ $record['report_number'] ] = $mappedData;

            }

            //$companySaferwebRepo->sync($company_id, $mappedData);

            return $records;

        } else {
            // Handle error or empty response
            return null;
        }

    }

}