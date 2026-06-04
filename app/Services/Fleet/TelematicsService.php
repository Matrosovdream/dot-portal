<?php

namespace App\Services\Fleet;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\SamsaraApi;

/**
 * @scaffold App-facing entry point for ELD / telematics data, fronting the
 * Samsara REST API via App\Mixins\Integrations\SamsaraApi.
 *
 * Credentials live under config('services.samsara') — api_token, base_url.
 *
 * Each method calls the client and maps Samsara's raw JSON into clean snake_case
 * domain arrays whose keys align to the related Eloquent models:
 *   - vehicles()           → App\Models\Vehicle (vin, number)
 *   - inspectionReports()  → App\Models\VehicleInspection (inspection_date, inspection_number)
 * Integration error arrays are passed straight through (AbstractIntegrationApi::failed).
 */
class TelematicsService
{
    public function __construct(private readonly SamsaraApi $api) {}

    /**
     * Normalised list of fleet vehicles.
     *
     * @return array<int, array{id:?string, name:?string, vin:?string, make:?string, model:?string, year:?string}>|array{error:bool, status:int, message:string}
     */
    public function vehicles(): array
    {
        $res = $this->api->vehicles();

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return array_values(array_map(
            fn ($vehicle) => $this->mapVehicle(is_array($vehicle) ? $vehicle : []),
            $res['data'] ?? []
        ));
    }

    /**
     * Normalised list of current vehicle locations.
     *
     * @return array<int, array{vehicle_id:?string, latitude:?float, longitude:?float, time:?string, speed_mph:?float}>|array{error:bool, status:int, message:string}
     */
    public function locations(): array
    {
        $res = $this->api->vehicleLocations();

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return array_values(array_map(
            fn ($entry) => $this->mapLocation(is_array($entry) ? $entry : []),
            $res['data'] ?? []
        ));
    }

    /**
     * Normalised hours-of-service (ELD) log entries for a time window. Times are
     * RFC 3339 timestamps as required by Samsara (e.g. 2024-01-01T00:00:00Z).
     *
     * @return array<int, array{vehicle_id:?string, driver_id:?string, driver_name:?string, log_start_time:?string, hos_status:?string}>|array{error:bool, status:int, message:string}
     */
    public function hoursOfService(string $start, string $end): array
    {
        $res = $this->api->hosLogs($start, $end);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return array_values(array_map(
            fn ($log) => $this->mapHosLog(is_array($log) ? $log : []),
            $res['data'] ?? []
        ));
    }

    /**
     * Normalised DVIR / inspection report history for a time window. Keys align
     * to vehicle_inspections (inspection_date, inspection_number).
     *
     * @return array<int, array{vehicle_id:?string, driver_id:?string, inspection_date:?string, inspection_number:?string, safety_status:?string, mechanic_notes:?string}>|array{error:bool, status:int, message:string}
     */
    public function inspectionReports(string $start, string $end): array
    {
        $res = $this->api->dvirs($start, $end);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return array_values(array_map(
            fn ($dvir) => $this->mapInspection(is_array($dvir) ? $dvir : []),
            $res['data'] ?? []
        ));
    }

    /**
     * Shape a Samsara vehicle object into Vehicle-aligned fields.
     */
    private function mapVehicle(array $vehicle): array
    {
        return [
            'id' => isset($vehicle['id']) ? (string) $vehicle['id'] : null,
            'name' => $vehicle['name'] ?? null,
            'vin' => $vehicle['vin'] ?? null,
            'make' => $vehicle['make'] ?? null,
            'model' => $vehicle['model'] ?? null,
            'year' => isset($vehicle['year']) ? (string) $vehicle['year'] : null,
        ];
    }

    /**
     * Shape a Samsara location entry. The GPS payload may be nested under a
     * `location` key; speed is reported in mph.
     */
    private function mapLocation(array $entry): array
    {
        $location = is_array($entry['location'] ?? null) ? $entry['location'] : $entry;

        return [
            'vehicle_id' => isset($entry['id']) ? (string) $entry['id'] : null,
            'latitude' => isset($location['latitude']) ? (float) $location['latitude'] : null,
            'longitude' => isset($location['longitude']) ? (float) $location['longitude'] : null,
            'time' => $location['time'] ?? null,
            'speed_mph' => isset($location['speedMilesPerHour'])
                ? (float) $location['speedMilesPerHour']
                : (isset($location['speed']) ? (float) $location['speed'] : null),
        ];
    }

    /**
     * Shape a Samsara HOS log entry.
     */
    private function mapHosLog(array $log): array
    {
        $driver = is_array($log['driver'] ?? null) ? $log['driver'] : [];
        $vehicle = is_array($log['vehicle'] ?? null) ? $log['vehicle'] : [];

        return [
            'vehicle_id' => isset($vehicle['id'])
                ? (string) $vehicle['id']
                : (isset($log['vehicleId']) ? (string) $log['vehicleId'] : null),
            'driver_id' => isset($driver['id'])
                ? (string) $driver['id']
                : (isset($log['driverId']) ? (string) $log['driverId'] : null),
            'driver_name' => $driver['name'] ?? $log['driverName'] ?? null,
            'log_start_time' => $log['logStartTime'] ?? $log['startTime'] ?? null,
            'hos_status' => $log['hosStatusType'] ?? $log['status'] ?? null,
        ];
    }

    /**
     * Shape a Samsara DVIR into vehicle_inspections-aligned fields.
     */
    private function mapInspection(array $dvir): array
    {
        $driver = is_array($dvir['driver'] ?? null) ? $dvir['driver'] : [];
        $vehicle = is_array($dvir['vehicle'] ?? null) ? $dvir['vehicle'] : [];

        return [
            'vehicle_id' => isset($vehicle['id'])
                ? (string) $vehicle['id']
                : (isset($dvir['vehicleId']) ? (string) $dvir['vehicleId'] : null),
            'driver_id' => isset($driver['id'])
                ? (string) $driver['id']
                : (isset($dvir['driverId']) ? (string) $dvir['driverId'] : null),
            'inspection_date' => $dvir['inspectionTime'] ?? $dvir['time'] ?? null,
            'inspection_number' => isset($dvir['id']) ? (string) $dvir['id'] : null,
            'safety_status' => $dvir['safetyStatus'] ?? null,
            'mechanic_notes' => $dvir['mechanicNotes'] ?? null,
        ];
    }
}
