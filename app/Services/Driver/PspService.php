<?php

namespace App\Services\Driver;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\PspApi;

/**
 * @scaffold App-facing service for FMCSA Pre-Employment Screening Program (PSP)
 * reports, fronting App\Mixins\Integrations\PspApi.
 *
 * Credentials live under config('services.psp') — base_url, account_id, api_key.
 *
 * Compliance: a PSP report (5yr crash / 3yr inspection history) is a consumer
 * report under the FCRA and is PAID per pull. It may only be ordered for
 * employment purposes with the applicant's signed consent; order() always sends
 * consent:true and the caller is responsible for having obtained and recorded
 * that consent beforehand.
 */
class PspService
{
    public function __construct(private readonly PspApi $api) {}

    /**
     * Order a PSP report for a driver applicant. Expects a driver array with
     * first_name, last_name, license_number and license_state.
     *
     * @return array{success:bool, report_id:?string, status:?string}|array{error:bool, status:int, message:string}
     */
    public function order(array $driver): array
    {
        $payload = [
            'first_name' => $driver['first_name'] ?? null,
            'last_name' => $driver['last_name'] ?? null,
            'license_number' => $driver['license_number'] ?? null,
            'license_state' => $driver['license_state'] ?? null,
            'consent' => true,
        ];

        $res = $this->api->orderReport($payload);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'report_id' => $res['report_id'] ?? $res['id'] ?? null,
            'status' => $res['status'] ?? 'pending',
        ];
    }

    /**
     * Fetch an ordered report and normalise it into a clean domain array. The
     * crash / inspection entries map onto the driver PSP history columns.
     *
     * @return array{status:?string, crashes:array, inspections:array, raw:array}|array{error:bool, status:int, message:string}
     */
    public function fetch(string $reportId): array
    {
        $res = $this->api->getReport($reportId);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $report = $res['report'] ?? $res;

        return [
            'status' => $res['status'] ?? $report['status'] ?? null,
            'crashes' => $this->mapCrashes($report['crashes'] ?? $report['crash_history'] ?? []),
            'inspections' => $this->mapInspections($report['inspections'] ?? $report['inspection_history'] ?? []),
            'raw' => $res,
        ];
    }

    /**
     * Flatten the PSP crash entries into a stable, model-aligned shape.
     */
    private function mapCrashes(array $crashes): array
    {
        return array_values(array_map(static function ($c) {
            $c = is_array($c) ? $c : [];

            return [
                'report_number' => $c['report_number'] ?? $c['crash_report_number'] ?? null,
                'crash_date' => $c['crash_date'] ?? $c['date'] ?? null,
                'state' => $c['state'] ?? $c['report_state'] ?? null,
                'fatalities' => $c['fatalities'] ?? $c['number_of_fatalities'] ?? null,
                'injuries' => $c['injuries'] ?? $c['number_of_injuries'] ?? null,
                'tow_away' => $c['tow_away'] ?? $c['towaway'] ?? null,
            ];
        }, $crashes));
    }

    /**
     * Flatten the PSP roadside-inspection entries into a stable, model-aligned shape.
     */
    private function mapInspections(array $inspections): array
    {
        return array_values(array_map(static function ($i) {
            $i = is_array($i) ? $i : [];

            return [
                'report_number' => $i['report_number'] ?? $i['inspection_report_number'] ?? null,
                'inspection_date' => $i['inspection_date'] ?? $i['date'] ?? null,
                'state' => $i['state'] ?? $i['report_state'] ?? null,
                'level' => $i['level'] ?? $i['inspection_level'] ?? null,
                'violations' => $this->mapViolations($i['violations'] ?? []),
            ];
        }, $inspections));
    }

    /**
     * Flatten the violation entries attached to an inspection.
     */
    private function mapViolations(array $violations): array
    {
        return array_values(array_map(static function ($v) {
            $v = is_array($v) ? $v : [];

            return [
                'code' => $v['code'] ?? $v['violation_code'] ?? null,
                'description' => $v['description'] ?? $v['desc'] ?? null,
                'oos' => $v['oos'] ?? $v['out_of_service'] ?? null,
            ];
        }, $violations));
    }
}
