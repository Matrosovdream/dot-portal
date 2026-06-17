<?php

namespace App\Services\Address;

use App\Contracts\Address\AddressSearch;
use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\Address\GeoapifyApi;

/**
 * App-facing entry point for US address autocomplete (Geoapify).
 *
 * Shapes input for the transport client, then maps the raw Geoapify result list
 * into a clean suggestion array. The mapped keys align to the address columns
 * shared by App\Models\UserAddress / DriverAddress / UserCompanyAddress so a
 * picked suggestion can be persisted with minimal translation:
 *
 *     line1   -> address1
 *     city    -> city
 *     state   -> state_id is a FK to ref_country_states; map the two-letter
 *                abbreviation to its state row before save
 *     zipcode -> zip
 *
 * `state` is returned as the USPS two-letter abbreviation rather than the numeric
 * state_id, mirroring AddressVerificationService::normalized.
 */
class AddressSearchService implements AddressSearch
{
    public function __construct(private readonly GeoapifyApi $api)
    {
    }

    public function suggest(string $text, int $limit = 5): array
    {
        $text = trim($text);

        // Skip the round-trip for empty input — there is nothing to autocomplete.
        if ($text === '') {
            return ['suggestions' => [], 'raw' => []];
        }

        $result = $this->api->autocomplete($text, $limit);

        if (AbstractIntegrationApi::failed($result)) {
            return $result;
        }

        $places = $result['results'] ?? [];

        return [
            'suggestions' => array_map([$this, 'mapSuggestion'], $places),
            'raw' => $result,
        ];
    }

    /**
     * Map a Geoapify result into model-aligned address parts.
     */
    private function mapSuggestion(array $place): array
    {
        return [
            'label' => $place['formatted'] ?? null,
            'line1' => $place['address_line1'] ?? null,
            'city' => $place['city'] ?? null,
            'state' => $place['state_code'] ?? null,
            'zipcode' => $place['postcode'] ?? null,
            'country' => $place['country_code'] ?? null,
            'lat' => $place['lat'] ?? null,
            'lon' => $place['lon'] ?? null,
            'place_id' => $place['place_id'] ?? null,
        ];
    }
}
