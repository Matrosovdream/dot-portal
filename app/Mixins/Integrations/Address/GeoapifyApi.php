<?php

namespace App\Mixins\Integrations\Address;

use App\Mixins\Integrations\AbstractIntegrationApi;
use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold Geoapify Address Autocomplete API client (transport layer).
 *
 * Provider: Geoapify (https://www.geoapify.com). The free tier allows ~3,000
 * requests/day, permits commercial use and needs no credit card to start, which
 * makes it the low-friction choice for type-ahead address search. Auth is a
 * single apiKey query parameter (config('services.geoapify.api_key')), attached
 * to every request in client().
 *
 * Endpoint: GET https://api.geoapify.com/v1/geocode/autocomplete
 * Results are restricted to the US via filter=countrycode:us and requested in
 * the flat `format=json` shape, so the body is { "results": [ {properties...} ] }
 * rather than a GeoJSON FeatureCollection.
 *
 * When unconfigured every call degrades to a clean error array (status 503) via
 * the base class — it never throws.
 */
class GeoapifyApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Geoapify Address Autocomplete API';
    protected string $baseUrl = 'https://api.geoapify.com/v1/geocode/';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) config('services.geoapify.api_key', '');
    }

    /**
     * Attach the Geoapify apiKey to every request's query string, keeping the
     * auth concern out of the domain methods (mirrors FmcsaQcMobileAPI).
     */
    protected function client(): PendingRequest
    {
        return parent::client()->withQueryParameters([
            'apiKey' => $this->apiKey,
        ]);
    }

    protected function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Autocomplete a partial US street address.
     *
     * Returns the decoded body — { 'results' => array<int,array> } on success,
     * or a normalised error array. `text` is the partial input; `limit` caps the
     * number of candidates.
     */
    public function autocomplete(string $text, int $limit = 5): array
    {
        return $this->get('autocomplete', [
            'text' => $text,
            'limit' => $limit,
            'filter' => 'countrycode:us',
            'format' => 'json',
        ]);
    }
}
