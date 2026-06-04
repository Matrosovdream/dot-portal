<?php

namespace App\Services\Vehicle;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\NhtsaVpicAPI;

/**
 * App-facing entry point for VIN decoding via NHTSA vPIC.
 *
 * Wraps the keyless NhtsaVpicAPI transport and maps its flat Results[0] field
 * map into a clean snake_case domain array. Of these fields only `vin` maps to
 * an existing `vehicles` column today (see Vehicle model); the rest are decoded
 * attributes the caller can persist into a future vehicle_vin_decode table or
 * surface in the UI. The original Results[0] map is preserved under `raw`.
 */
class VinDecodeService
{
    public function __construct(private readonly NhtsaVpicAPI $api) {}

    /**
     * Decode a single VIN into a normalised domain array.
     *
     * On success: a flat map of vehicle attributes plus 'raw' => Results[0].
     * On failure: the integration error array is passed straight through.
     */
    public function decode(string $vin, ?int $year = null): array
    {
        $result = $this->api->decodeVin($vin, $year);

        if (AbstractIntegrationApi::failed($result)) {
            return $result;
        }

        $row = $result['Results'][0] ?? [];

        return $this->mapDecoded($vin, $row);
    }

    /**
     * Shape a vPIC Results[0] field map into snake_case domain fields.
     */
    private function mapDecoded(string $vin, array $row): array
    {
        return [
            'vin' => $this->clean($row['VIN'] ?? null) ?? $vin,
            'make' => $this->clean($row['Make'] ?? null),
            'model' => $this->clean($row['Model'] ?? null),
            'model_year' => $this->clean($row['ModelYear'] ?? null),
            'manufacturer' => $this->clean($row['Manufacturer'] ?? null),
            'vehicle_type' => $this->clean($row['VehicleType'] ?? null),
            'body_class' => $this->clean($row['BodyClass'] ?? null),
            'gvwr' => $this->clean($row['GVWR'] ?? null),
            'fuel_type_primary' => $this->clean($row['FuelTypePrimary'] ?? null),
            'engine_cylinders' => $this->clean($row['EngineCylinders'] ?? null),
            'displacement_l' => $this->clean($row['DisplacementL'] ?? null),
            'plant_country' => $this->clean($row['PlantCountry'] ?? null),
            'error_code' => $this->clean($row['ErrorCode'] ?? null),
            'error_text' => $this->clean($row['ErrorText'] ?? null),
            'raw' => $row,
        ];
    }

    /**
     * vPIC returns empty strings for unknown fields; normalise those to null.
     */
    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
