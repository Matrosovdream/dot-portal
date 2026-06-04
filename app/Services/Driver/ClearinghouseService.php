<?php

namespace App\Services\Driver;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\ClearinghouseApi;

/**
 * @scaffold App-facing service for the FMCSA Drug & Alcohol Clearinghouse,
 * fronting a C/TPA partner endpoint via App\Mixins\Integrations\ClearinghouseApi.
 * The FMCSA publishes NO open REST API; queries are brokered through a registered
 * C/TPA partner. Powers the dashboard "Clearinghouse Management" module.
 *
 * Credentials live under config('services.clearinghouse') — base_url, api_key.
 *
 * Compliance: a query is only permissible with the driver's electronic consent
 * recorded in the Clearinghouse (general consent for 'limited', specific consent
 * for 'full'). query() always sends consent:true; the caller is responsible for
 * having obtained and recorded that consent beforehand. A returned violation maps
 * onto App\Models\DriverDrugTest (driver_drug_test.test_date / test_type /
 * result) for the driver's drug & alcohol record.
 */
class ClearinghouseService
{
    public function __construct(private readonly ClearinghouseApi $api) {}

    /**
     * Submit a Clearinghouse query for a driver. Expects a driver array with
     * first_name, last_name, license_number, license_state and dob (YYYY-MM-DD).
     * The $type is 'limited' (annual general-consent check) or 'full'.
     *
     * @return array{success:bool, query_id:?string, status:?string}|array{error:bool, status:int, message:string}
     */
    public function query(array $driver, string $type = 'limited'): array
    {
        $type = in_array($type, ['limited', 'full'], true) ? $type : 'limited';

        $payload = [
            'driver' => [
                'first_name' => $driver['first_name'] ?? null,
                'last_name' => $driver['last_name'] ?? null,
                'dob' => $driver['dob'] ?? null,
            ],
            'license' => [
                'number' => $driver['license_number'] ?? null,
                'state' => $driver['license_state'] ?? null,
            ],
            'type' => $type,
            'consent' => true,
        ];

        $res = $this->api->submitQuery($payload);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'query_id' => $res['query_id'] ?? $res['id'] ?? null,
            'status' => $res['status'] ?? 'pending',
        ];
    }

    /**
     * Fetch a submitted query and normalise it into a clean domain array. Any
     * reported violation aligns to the driver_drug_test columns (test_date,
     * test_type, result) so the Clearinghouse Management module can persist it.
     *
     * @return array{status:?string, violations:array, queried_at:?string, raw:array}|array{error:bool, status:int, message:string}
     */
    public function result(string $queryId): array
    {
        $res = $this->api->getQuery($queryId);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $query = $res['query'] ?? $res;

        return [
            'status' => $res['status'] ?? $query['status'] ?? null,
            'violations' => $this->mapViolations($query['violations'] ?? []),
            'queried_at' => $res['queried_at'] ?? $query['queried_at'] ?? $query['completed_at'] ?? null,
            'raw' => $res,
        ];
    }

    /**
     * Flatten the partner's violation entries into a stable shape whose keys map
     * onto the driver_drug_test table (test_date / test_type / result).
     */
    private function mapViolations(array $violations): array
    {
        return array_values(array_map(static function ($v) {
            $v = is_array($v) ? $v : [];

            return [
                'test_date' => $v['test_date'] ?? $v['violation_date'] ?? $v['date'] ?? null,
                'test_type' => $v['test_type'] ?? $v['type'] ?? null,
                'result' => $v['result'] ?? $v['status'] ?? null,
                'description' => $v['description'] ?? $v['desc'] ?? null,
            ];
        }, $violations));
    }
}
