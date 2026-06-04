<?php

namespace App\Services\Fmcsa;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\FmcsaQcMobileAPI;

/**
 * App-facing entry point for FMCSA QCMobile carrier data.
 *
 * Wraps FmcsaQcMobileAPI and maps the raw FMCSA JSON into clean snake_case
 * domain arrays whose keys align to the company_saferweb / user_company
 * tables (see App\Models\CompanySaferweb, App\Models\UserCompany). Error
 * arrays from the transport layer are passed straight through.
 */
class CarrierService
{
    public function __construct(private readonly FmcsaQcMobileAPI $api) {}

    /**
     * Carrier snapshot by USDOT number, shaped for persistence.
     *
     * Column alignment:
     *   usdot / mc_number      -> company_saferweb.dot_number / mc_number
     *                             (also user_company.dot_number / mc_number)
     *   legal_name / dba_name  -> company_saferweb.legal_name / dba_name
     *   physical_address       -> company_saferweb.physical_address
     *   phone                  -> user_company.phone
     *   total_drivers          -> user_company.drivers_number
     *   total_power_units      -> user_company.trucks_number
     */
    public function snapshot(string $usdot): array
    {
        $res = $this->api->carrierByDot($usdot);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $carrier = $res['content']['carrier'] ?? $res['content'] ?? [];

        return $this->mapCarrier($carrier, $usdot);
    }

    /**
     * Normalised BASIC measures by USDOT number.
     *
     * Returns a flat list of BASIC categories with their measure values and
     * threshold/over-threshold flags, plus the source usdot.
     */
    public function basics(string $usdot): array
    {
        $res = $this->api->basicsByDot($usdot);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $content = $res['content'] ?? [];

        $measures = array_map(
            fn ($row) => $this->mapBasic(is_array($row) ? $row : []),
            array_values($content)
        );

        return [
            'usdot' => $usdot,
            'basics' => $measures,
        ];
    }

    /**
     * Map a raw FMCSA carrier block to the domain/snapshot shape.
     */
    private function mapCarrier(array $c, string $usdot): array
    {
        $physical = $this->joinAddress([
            $c['phyStreet'] ?? null,
            $c['phyCity'] ?? null,
            $c['phyState'] ?? null,
            $c['phyZipcode'] ?? null,
            $c['phyCountry'] ?? null,
        ]);

        return [
            'usdot' => (string) ($c['dotNumber'] ?? $usdot),
            'dba_name' => $this->str($c['dbaName'] ?? null),
            'legal_name' => $this->str($c['legalName'] ?? null),
            'phone' => $this->str($c['phone'] ?? null),
            'physical_address' => $physical,
            'total_drivers' => $this->intOrNull($c['totalDrivers'] ?? null),
            'total_power_units' => $this->intOrNull($c['totalPowerUnits'] ?? null),
            'safety_rating' => $this->str($c['safetyRating'] ?? null),
            'allowed_to_operate' => $this->yesNoToBool($c['allowedToOperate'] ?? null),
            'out_of_service_date' => $this->str($c['oosDate'] ?? null),
            'mc_number' => $this->str($c['mcNumber'] ?? $c['docketNumber'] ?? null),
        ];
    }

    /**
     * Map a raw FMCSA BASIC row to a normalised measure.
     */
    private function mapBasic(array $b): array
    {
        $basic = $b['basic'] ?? $b;

        return [
            'code' => $this->str($basic['basicsCode'] ?? null),
            'category' => $this->str($basic['basicsType']['basicsCodeName'] ?? $basic['basicsType']['basicsLongDesc'] ?? null),
            'measure' => $this->floatOrNull($basic['measureValue'] ?? $basic['measure'] ?? null),
            'percentile' => $this->floatOrNull($basic['basicsPercentile'] ?? $basic['percentile'] ?? null),
            'serious_violation' => $this->yesNoToBool($basic['seriousViolationFromInspFlag'] ?? null),
            'over_threshold' => $this->yesNoToBool($basic['exceededFMCSAInterventionThreshold'] ?? $basic['overIndicator'] ?? null),
            'run_date' => $this->str($basic['basicsRunDate'] ?? null),
        ];
    }

    private function joinAddress(array $parts): ?string
    {
        $parts = array_filter(array_map(
            fn ($p) => is_string($p) ? trim($p) : (is_null($p) ? '' : trim((string) $p)),
            $parts
        ), fn ($p) => $p !== '');

        return $parts ? implode(', ', $parts) : null;
    }

    private function str($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function intOrNull($value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function floatOrNull($value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * FMCSA returns 'Y'/'N' (and occasionally bool) for yes/no flags.
     */
    private function yesNoToBool($value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        $value = strtoupper(trim((string) $value));

        return match ($value) {
            'Y', 'YES', 'TRUE', '1' => true,
            'N', 'NO', 'FALSE', '0' => false,
            default => null,
        };
    }
}
