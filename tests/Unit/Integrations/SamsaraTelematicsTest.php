<?php

namespace Tests\Unit\Integrations;

use App\Services\Fleet\TelematicsService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the Samsara telematics integration (SamsaraApi transport +
 * TelematicsService mapping). Every call is stubbed with Http::fake(), so no real
 * network traffic occurs and the database is never touched.
 *
 * SamsaraApi reads its credentials in the constructor, so each test sets
 * config('services.samsara.*') BEFORE resolving TelematicsService from the container.
 */
class SamsaraTelematicsTest extends TestCase
{
    /**
     * Set valid Samsara credentials. Must run before app(TelematicsService::class)
     * because the client snapshots the token / base URL in its constructor.
     */
    private function configureCredentials(): void
    {
        config()->set('services.samsara.api_token', 'test-samsara-token');
        config()->set('services.samsara.base_url', 'https://api.samsara.com');
    }

    public function test_vehicles_maps_samsara_payload_to_domain_array(): void
    {
        $this->configureCredentials();

        $body = [
            'data' => [
                [
                    'id' => 281474976710655,
                    'name' => 'Truck 21',
                    'vin' => '1FUJGLDR9CSBP1234',
                    'make' => 'FREIGHTLINER',
                    'model' => 'CASCADIA',
                    'year' => 2019,
                ],
                [
                    'id' => 281474976710656,
                    'name' => 'Truck 22',
                    'vin' => '3AKJGLDR5GSGZ5678',
                    'make' => 'KENWORTH',
                    'model' => 'T680',
                    'year' => 2021,
                ],
            ],
        ];

        Http::fake([
            'api.samsara.com/*' => Http::response($body, 200),
        ]);

        $result = app(TelematicsService::class)->vehicles();

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('error', $result);
        $this->assertCount(2, $result);

        $first = $result[0];
        $this->assertSame(
            ['id', 'name', 'vin', 'make', 'model', 'year'],
            array_keys($first)
        );

        // Numeric Samsara id / year are normalised to strings by the mapper.
        $this->assertSame('281474976710655', $first['id']);
        $this->assertSame('Truck 21', $first['name']);
        $this->assertSame('1FUJGLDR9CSBP1234', $first['vin']);
        $this->assertSame('FREIGHTLINER', $first['make']);
        $this->assertSame('CASCADIA', $first['model']);
        $this->assertSame('2019', $first['year']);

        $this->assertSame('KENWORTH', $result[1]['make']);
        $this->assertSame('2021', $result[1]['year']);
    }

    public function test_vehicles_sends_get_with_bearer_token_to_fleet_vehicles(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.samsara.com/*' => Http::response(['data' => []], 200),
        ]);

        app(TelematicsService::class)->vehicles();

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'fleet/vehicles')
                && $request->hasHeader('Authorization', 'Bearer test-samsara-token');
        });
    }

    public function test_locations_maps_nested_gps_and_speed(): void
    {
        $this->configureCredentials();

        $body = [
            'data' => [
                [
                    'id' => 212014918583296,
                    'name' => 'Truck 21',
                    'location' => [
                        'latitude' => 37.0,
                        'longitude' => -122.0,
                        'time' => '2024-01-01T00:00:00Z',
                        'speedMilesPerHour' => 55.5,
                    ],
                ],
            ],
        ];

        Http::fake([
            'api.samsara.com/*' => Http::response($body, 200),
        ]);

        $result = app(TelematicsService::class)->locations();

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('error', $result);
        $this->assertCount(1, $result);

        $first = $result[0];
        $this->assertSame(
            ['vehicle_id', 'latitude', 'longitude', 'time', 'speed_mph'],
            array_keys($first)
        );

        $this->assertSame('212014918583296', $first['vehicle_id']);
        $this->assertSame(37.0, $first['latitude']);
        $this->assertSame(-122.0, $first['longitude']);
        $this->assertSame('2024-01-01T00:00:00Z', $first['time']);
        $this->assertSame(55.5, $first['speed_mph']);
    }

    public function test_locations_sends_get_with_bearer_token_to_locations_endpoint(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.samsara.com/*' => Http::response(['data' => []], 200),
        ]);

        app(TelematicsService::class)->locations();

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'fleet/vehicles/locations')
                && $request->hasHeader('Authorization', 'Bearer test-samsara-token');
        });
    }

    public function test_vehicles_returns_503_error_when_token_not_configured(): void
    {
        // Empty token => isConfigured() is false => no request is ever made.
        config()->set('services.samsara.api_token', '');
        config()->set('services.samsara.base_url', 'https://api.samsara.com');

        Http::fake([
            'api.samsara.com/*' => Http::response(['data' => []], 200),
        ]);

        $result = app(TelematicsService::class)->vehicles();

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);
        $this->assertArrayHasKey('message', $result);

        Http::assertNothingSent();
    }

    public function test_locations_passes_through_upstream_error_status(): void
    {
        $this->configureCredentials();

        Http::fake([
            'api.samsara.com/*' => Http::response(['message' => 'Internal Server Error'], 500),
        ]);

        $result = app(TelematicsService::class)->locations();

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
        $this->assertSame('Internal Server Error', $result['message']);
    }
}
