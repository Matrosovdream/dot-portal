<?php

namespace App\Helpers\User;

use App\Mixins\Integrations\SaferwebAPI;
use App\Repositories\User\UserCompanyRepo;
use Log;

class CompanyHelper {

    public function updateSnapshot(int $company_id): array|null
    {

        Log::info("TT {$company_id}");

        $apiService = app(SaferwebAPI::class);
        $companyRepo = app(UserCompanyRepo::class);

        $company = $companyRepo->getByID($company_id);
        $dotNumber = $company['dot_number'] ?? null;

        if ($dotNumber) {
            $apiData = $apiService->getCompanySnapshot($dotNumber);
        } else { return null; }

        if ( 
            empty($apiData['error']) &&
            $apiData != null
            ) { 
            $company['Model']->update([
                'mc_number' => $apiData['phone'],
            ]);

            return $apiData;

        } else {
            // Handle error or empty response
            return null;
        }
    }

}