<?php

namespace Tests\Unit\Integrations;

use App\Services\Driver\MvrService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the MVR aggregator integration (MvrService → MvrApi).
 *
 * No database, no real network: every call is stubbed via Http::fake(). Credentials
 * are read in MvrApi's constructor, so each test configures services.mvr.* BEFORE
 * resolving the service from the container.
 */
class MvrServiceTest extends TestCase
{
    private function configure(string $baseUrl = 'https://mvr.example.test/', string $apiKey = 'k'): void
    {
        config()->set('services.mvr.base_url', $baseUrl);
        config()->set('services.mvr.api_key', $apiKey);
        config()->set('services.mvr.provider', 'sambasafety');
    }

    private function driver(): array
    {
        return [
            'first_name' => 'JOHN',
            'last_name' => 'DRIVER',
            'license_number' => 'D1234567',
            'license_state' => 'CA',
            'dob' => '1990-05-04',
        ];
    }

    public function test_order_maps_success_and_sends_post_with_bearer_auth(): void
    {
        $this->configure();

        Http::fake([
            'mvr.example.test/*' => Http::response([
                'order_id' => 'o1',
                'status' => 'pending',
            ], 200),
        ]);

        $service = app(MvrService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['success']);
        $this->assertSame('o1', $result['order_id']);
        $this->assertSame('pending', $result['status']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'orders')
                && $request->hasHeader('Authorization', 'Bearer k')
                // Service always forces the captured-consent flag.
                && $request['consent'] === true
                && $request['license_number'] === 'D1234567';
        });
    }

    public function test_fetch_maps_completed_record_and_violations(): void
    {
        $this->configure();

        Http::fake([
            'mvr.example.test/*' => Http::response([
                'status' => 'complete',
                'mvr_number' => 'MVR-1',
                'issued_date' => '2025-01-01',
                'violations' => [
                    [
                        'code' => 'SPD',
                        'description' => 'Speeding 15 over',
                        'date' => '2024-08-12',
                        'points' => 2,
                    ],
                ],
            ], 200),
        ]);

        $service = app(MvrService::class);

        $result = $service->fetch('o1');

        $this->assertSame('complete', $result['status']);
        $this->assertSame('MVR-1', $result['mvr_number']);
        $this->assertSame('2025-01-01', $result['issued_date']);

        $this->assertCount(1, $result['violations']);
        $this->assertSame('SPD', $result['violations'][0]['code']);
        $this->assertSame('Speeding 15 over', $result['violations'][0]['description']);
        $this->assertSame('2024-08-12', $result['violations'][0]['date']);
        $this->assertSame(2, $result['violations'][0]['points']);

        // raw is the untouched provider body.
        $this->assertSame('MVR-1', $result['raw']['mvr_number']);
    }

    public function test_fetch_sends_get_to_order_endpoint_with_bearer_auth(): void
    {
        $this->configure();

        Http::fake([
            'mvr.example.test/*' => Http::response([
                'status' => 'pending',
            ], 200),
        ]);

        $service = app(MvrService::class);

        $service->fetch('o1');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'orders/o1')
                && $request->hasHeader('Authorization', 'Bearer k');
        });
    }

    public function test_order_returns_503_when_not_configured(): void
    {
        // Empty credentials → MvrApi::isConfigured() is false → clean 503, no call.
        $this->configure('', '');

        Http::fake([
            'mvr.example.test/*' => Http::response([], 200),
        ]);

        $service = app(MvrService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_fetch_returns_503_when_not_configured(): void
    {
        $this->configure('', '');

        Http::fake([
            'mvr.example.test/*' => Http::response([], 200),
        ]);

        $service = app(MvrService::class);

        $result = $service->fetch('o1');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_order_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'mvr.example.test/*' => Http::response([
                'message' => 'Upstream aggregator failure',
            ], 500),
        ]);

        $service = app(MvrService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_fetch_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'mvr.example.test/*' => Http::response([
                'message' => 'Order not found',
            ], 422),
        ]);

        $service = app(MvrService::class);

        $result = $service->fetch('missing');

        $this->assertTrue($result['error']);
        $this->assertSame(422, $result['status']);
    }
}
