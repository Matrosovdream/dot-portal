<?php

namespace App\Services\Saferweb;

use App\Contracts\Saferweb\SaferwebInterface;


class GovernApiService implements SaferwebInterface {

    public function retrieveUsdotData( string $usdot ): array {

        $dotApi = app('App\Helpers\TranspGov\TranspGovSnapshot');

        // Use the TranspGovSnapshot helper to retrieve USDOT information
        $snapshot = $dotApi->getByDot($usdot);

        if (!$snapshot) {
            return [];
        }
        
        // to retrieve the USDOT information. For this example, we'll just return a dummy response.
        $response = [
            'usdot' => $usdot,
            'mc_number' => $snapshot['mc_number'] ?? '',
            'company_name' => $snapshot['legal_name'] ?? '',
            'trucks_number' => $snapshot['truck_units'] ?? 0,
            'drivers_number' => $snapshot['total_drivers'] ?? 0,
            'phone' => $snapshot['phone'] ?? '',
        ];

        return $response;

    }

}