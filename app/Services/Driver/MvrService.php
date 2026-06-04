<?php

namespace App\Services\Driver;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\MvrApi;

/**
 * @scaffold App-facing service for Motor Vehicle Record (MVR) orders, fronting a
 * provider-agnostic aggregator (e.g. SambaSafety) via App\Mixins\Integrations\MvrApi.
 *
 * Credentials live under config('services.mvr') — base_url, api_key, provider.
 *
 * Compliance: MVR pulls are governed by the DPPA and, for employment use, the
 * FCRA. An order is only permissible with the driver's signed consent; order()
 * always sends consent:true and the caller is responsible for having obtained
 * and recorded that consent beforehand. Results map onto App\Models\DriverMvr
 * (driver_mvr.mvr_number / mvr_date).
 */
class MvrService
{
    public function __construct(private readonly MvrApi $api) {}

    /**
     * Order an MVR for a driver. Expects a driver array with first_name,
     * last_name, license_number, license_state and dob (YYYY-MM-DD).
     *
     * @return array{success:bool, order_id:?string, status:?string}|array{error:bool, status:int, message:string}
     */
    public function order(array $driver): array
    {
        $payload = [
            'first_name' => $driver['first_name'] ?? null,
            'last_name' => $driver['last_name'] ?? null,
            'license_number' => $driver['license_number'] ?? null,
            'license_state' => $driver['license_state'] ?? null,
            'dob' => $driver['dob'] ?? null,
            'consent' => true,
        ];

        $res = $this->api->orderRecord($payload);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'order_id' => $res['order_id'] ?? $res['id'] ?? null,
            'status' => $res['status'] ?? 'pending',
        ];
    }

    /**
     * Fetch a placed order and normalise it into a clean domain array whose keys
     * align to the driver_mvr table (mvr_number; issued_date → mvr_date).
     *
     * @return array{status:?string, mvr_number:?string, issued_date:?string, violations:array, raw:array}|array{error:bool, status:int, message:string}
     */
    public function fetch(string $orderId): array
    {
        $res = $this->api->getRecord($orderId);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $record = $res['record'] ?? $res;

        return [
            'status' => $res['status'] ?? $record['status'] ?? null,
            'mvr_number' => $record['mvr_number'] ?? $record['report_number'] ?? null,
            'issued_date' => $record['issued_date'] ?? $record['issue_date'] ?? null,
            'violations' => $this->mapViolations($record['violations'] ?? []),
            'raw' => $res,
        ];
    }

    /**
     * Flatten the aggregator's violation entries into a stable shape.
     */
    private function mapViolations(array $violations): array
    {
        return array_values(array_map(static function ($v) {
            $v = is_array($v) ? $v : [];

            return [
                'code' => $v['code'] ?? $v['violation_code'] ?? null,
                'description' => $v['description'] ?? $v['desc'] ?? null,
                'date' => $v['date'] ?? $v['violation_date'] ?? null,
                'points' => $v['points'] ?? null,
            ];
        }, $violations));
    }
}
