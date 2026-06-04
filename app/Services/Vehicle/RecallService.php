<?php

namespace App\Services\Vehicle;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\NhtsaSafetyAPI;

/**
 * App-facing entry point for NHTSA vehicle safety lookups.
 *
 * Validates input, calls the NHTSA client, and maps the raw government payload
 * into clean snake_case domain arrays. Error arrays from the client are passed
 * straight through unchanged.
 */
class RecallService
{
    public function __construct(private readonly NhtsaSafetyAPI $api) {}

    /**
     * Recall campaigns for a vehicle.
     *
     * @return array{count:int,recalls:array<int,array<string,mixed>>}|array<string,mixed>
     */
    public function forVehicle(string $make, string $model, int $year): array
    {
        $res = $this->api->recallsByVehicle(trim($make), trim($model), $year);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $recalls = array_map(
            fn (array $row): array => $this->mapRecall($row),
            $res['results'] ?? []
        );

        return [
            'count' => count($recalls),
            'recalls' => $recalls,
        ];
    }

    /**
     * Owner complaints for a vehicle.
     *
     * @return array{count:int,complaints:array<int,array<string,mixed>>}|array<string,mixed>
     */
    public function complaints(string $make, string $model, int $year): array
    {
        $res = $this->api->complaintsByVehicle(trim($make), trim($model), $year);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $complaints = array_map(
            fn (array $row): array => $this->mapComplaint($row),
            $res['results'] ?? []
        );

        return [
            'count' => count($complaints),
            'complaints' => $complaints,
        ];
    }

    /**
     * Map a single NHTSA recall result into domain-aligned keys.
     */
    private function mapRecall(array $row): array
    {
        return [
            'campaign_number' => $row['NHTSACampaignNumber'] ?? null,
            'component' => $row['Component'] ?? null,
            'summary' => $row['Summary'] ?? null,
            'consequence' => $row['Consequence'] ?? null,
            'remedy' => $row['Remedy'] ?? null,
            'report_received_date' => $row['ReportReceivedDate'] ?? null,
        ];
    }

    /**
     * Map a single NHTSA complaint result into domain-aligned keys.
     */
    private function mapComplaint(array $row): array
    {
        return [
            'odi_number' => $row['odiNumber'] ?? null,
            'component' => $row['components'] ?? null,
            'summary' => $row['summary'] ?? null,
            'crash' => $row['crash'] ?? null,
            'fire' => $row['fire'] ?? null,
            'number_of_injuries' => $row['numberOfInjuries'] ?? null,
            'number_of_deaths' => $row['numberOfDeaths'] ?? null,
            'date_complaint_filed' => $row['dateComplaintFiled'] ?? null,
        ];
    }
}
