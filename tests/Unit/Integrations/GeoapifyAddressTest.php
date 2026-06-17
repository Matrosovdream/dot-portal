<?php

namespace Tests\Unit\Integrations;

use App\Services\Address\AddressSearchService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the Geoapify address autocomplete integration.
 *
 * Drives App\Services\Address\AddressSearchService (which wraps the GeoapifyApi
 * transport client) with Http::fake() dummy result bodies — no real network
 * calls and no database. The api key is injected per-test via config() BEFORE the
 * service is resolved, because GeoapifyApi reads it in its constructor.
 */
class GeoapifyAddressTest extends TestCase
{
    /**
     * A representative Geoapify `format=json` body: a { results: [...] } object
     * whose entries carry the flat property fields AddressSearchService reads
     * (formatted, address_line1, city, state_code, postcode, country_code,
     * lat, lon, place_id).
     */
    private function dummyResults(): array
    {
        return [
            'results' => [
                [
                    'formatted' => '1600 Amphitheatre Pkwy, Mountain View, CA 94043, United States of America',
                    'address_line1' => '1600 Amphitheatre Pkwy',
                    'address_line2' => 'Mountain View, CA 94043, United States of America',
                    'housenumber' => '1600',
                    'street' => 'Amphitheatre Pkwy',
                    'city' => 'Mountain View',
                    'state' => 'California',
                    'state_code' => 'CA',
                    'postcode' => '94043',
                    'country' => 'United States',
                    'country_code' => 'us',
                    'lat' => 37.4224,
                    'lon' => -122.0856,
                    'place_id' => '51abc123',
                    'result_type' => 'building',
                ],
            ],
            'query' => ['text' => '1600 Amphitheatre'],
        ];
    }

    private function configureApiKey(): void
    {
        config()->set('services.geoapify.api_key', 'test-api-key');
    }

    public function test_suggest_maps_results_into_model_aligned_suggestions(): void
    {
        $this->configureApiKey();

        Http::fake([
            'api.geoapify.com/*' => Http::response($this->dummyResults(), 200),
        ]);

        $service = app(AddressSearchService::class);

        $result = $service->suggest('1600 Amphitheatre');

        $this->assertCount(1, $result['suggestions']);

        $this->assertSame([
            'label' => '1600 Amphitheatre Pkwy, Mountain View, CA 94043, United States of America',
            'line1' => '1600 Amphitheatre Pkwy',
            'city' => 'Mountain View',
            'state' => 'CA',
            'zipcode' => '94043',
            'country' => 'us',
            'lat' => 37.4224,
            'lon' => -122.0856,
            'place_id' => '51abc123',
        ], $result['suggestions'][0]);

        // The raw provider body is passed straight through.
        $this->assertSame('building', $result['raw']['results'][0]['result_type']);

        // Called without an explicit limit, the default (5) must reach the wire.
        Http::assertSent(fn (Request $request) => (string) $request['limit'] === '5');
    }

    public function test_suggest_maps_sparse_result_with_null_for_missing_fields(): void
    {
        $this->configureApiKey();

        // Geoapify omits fields for partial/street-level matches; every mapped
        // key must fall back to null rather than emit an undefined-key warning.
        Http::fake([
            'api.geoapify.com/*' => Http::response([
                'results' => [
                    ['formatted' => 'Amphitheatre Pkwy', 'place_id' => 'sparse99'],
                ],
            ], 200),
        ]);

        $service = app(AddressSearchService::class);

        $result = $service->suggest('Amphitheatre');

        $this->assertSame([
            'label' => 'Amphitheatre Pkwy',
            'line1' => null,
            'city' => null,
            'state' => null,
            'zipcode' => null,
            'country' => null,
            'lat' => null,
            'lon' => null,
            'place_id' => 'sparse99',
        ], $result['suggestions'][0]);
    }

    public function test_suggest_returns_no_suggestions_for_empty_or_absent_results(): void
    {
        $this->configureApiKey();

        // Zero-match (200 with results: []) and a body missing the results key
        // are both successful, non-error responses that must yield no suggestions.
        Http::fake([
            'api.geoapify.com/*' => Http::sequence()
                ->push(['results' => [], 'query' => ['text' => 'zzz']], 200)
                ->push(['query' => ['text' => 'x']], 200),
        ]);

        $service = app(AddressSearchService::class);

        $zeroMatch = $service->suggest('zzz');
        $this->assertSame([], $zeroMatch['suggestions']);
        $this->assertSame('zzz', $zeroMatch['raw']['query']['text']);

        $absentKey = $service->suggest('x');
        $this->assertSame([], $absentKey['suggestions']);
    }

    public function test_suggest_sends_get_to_autocomplete_restricted_to_us_with_api_key(): void
    {
        $this->configureApiKey();

        Http::fake([
            'api.geoapify.com/*' => Http::response($this->dummyResults(), 200),
        ]);

        $service = app(AddressSearchService::class);

        $service->suggest('1600 Amphitheatre', 7);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'api.geoapify.com/v1/geocode/autocomplete')
                && $request['text'] === '1600 Amphitheatre'
                && (string) $request['limit'] === '7'
                // US-only results and the flat json shape the service maps.
                && $request['filter'] === 'countrycode:us'
                && $request['format'] === 'json'
                // Geoapify authenticates via the apiKey query param (attached in
                // client(), so it lands in the URL rather than the request data).
                && str_contains($request->url(), 'apiKey=test-api-key');
        });
    }

    public function test_suggest_short_circuits_blank_input_without_calling_the_api(): void
    {
        $this->configureApiKey();

        Http::fake([
            'api.geoapify.com/*' => Http::response($this->dummyResults(), 200),
        ]);

        $service = app(AddressSearchService::class);

        $result = $service->suggest('   ');

        $this->assertSame([], $result['suggestions']);
        // The early return must still honour the full contract shape.
        $this->assertSame([], $result['raw']);
        Http::assertNothingSent();
    }

    public function test_suggest_returns_503_error_when_not_configured(): void
    {
        config()->set('services.geoapify.api_key', '');

        Http::fake([
            'api.geoapify.com/*' => Http::response($this->dummyResults(), 200),
        ]);

        $service = app(AddressSearchService::class);

        $result = $service->suggest('1600 Amphitheatre');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        // Unconfigured clients must never reach the wire.
        Http::assertNothingSent();
    }

    public function test_suggest_passes_through_upstream_error_status(): void
    {
        $this->configureApiKey();

        Http::fake([
            'api.geoapify.com/*' => Http::response(['message' => 'boom'], 401),
        ]);

        $service = app(AddressSearchService::class);

        $result = $service->suggest('1600 Amphitheatre');

        $this->assertTrue($result['error']);
        $this->assertSame(401, $result['status']);
    }
}
