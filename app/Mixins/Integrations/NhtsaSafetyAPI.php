<?php

namespace App\Mixins\Integrations;

/**
 * NHTSA Safety API client — recalls & complaints for a given vehicle.
 *
 * Public, keyless government API (api.nhtsa.gov). No credentials are required,
 * so client() / isConfigured() use the base-class defaults. Every call returns
 * the decoded JSON body (array) on success or a normalised error array via the
 * base class.
 */
class NhtsaSafetyAPI extends AbstractIntegrationApi
{
    protected string $apiTitle = 'NHTSA Safety API';
    protected string $baseUrl = 'https://api.nhtsa.gov/';

    /**
     * Recall campaigns for a make/model/year.
     *
     * GET recalls/recallsByVehicle?make=&model=&modelYear=
     */
    public function recallsByVehicle(string $make, string $model, int $year): array
    {
        return $this->get('recalls/recallsByVehicle', [
            'make' => $make,
            'model' => $model,
            'modelYear' => $year,
        ]);
    }

    /**
     * Owner complaints for a make/model/year.
     *
     * GET complaints/complaintsByVehicle?make=&model=&modelYear=
     */
    public function complaintsByVehicle(string $make, string $model, int $year): array
    {
        return $this->get('complaints/complaintsByVehicle', [
            'make' => $make,
            'model' => $model,
            'modelYear' => $year,
        ]);
    }
}
