<?php

namespace App\Helpers;

use App\Mixins\Integrations\SaferwebAPI;
use App\Repositories\User\UserRepo;

class UserTaskHelper {

    public function updateCompanySnapshot(int $user_id): array|null
    {

        $apiService = new SaferwebAPI();
        $userRepo = new UserRepo();

        $user = $userRepo->getByID($user_id);
        $dotNumber = $user['company']['dot_number'] ?? null;

        if ($dotNumber) {
            $apiData = $apiService->getCompanySnapshot($dotNumber);
        } 

        if ( 
            empty($apiData['error']) &&
            $apiData != null
            ) { 
            $user['Model']->company->update([
                'mc_number' => $apiData['phone'],
            ]);

            return $apiData;

        } else {
            // Handle error or empty response
            return null;
        }
    }

}
