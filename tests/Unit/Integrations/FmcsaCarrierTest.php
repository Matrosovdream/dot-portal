<?php

namespace Tests\Unit\Integrations;

use App\Services\Fmcsa\CarrierService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the FMCSA QCMobile integration (transport + mapping).
 *
 * No database and no real network: every request is intercepted with
 * Http::fake() against the client's base host. The client reads its webKey from
 * config('services.fmcsa.web_key') in its CONSTRUCTOR, so each test sets the
 * config and fakes HTTP BEFORE resolving CarrierService from the container.
 */
class FmcsaCarrierTest extends TestCase
{
    /**
     * Representative FMCSA carrier payload. Field names mirror the real
     * QCMobile response and, crucially, the exact keys CarrierService::mapCarrier()
     * reads (legalName, dbaName, phone, phy* parts, totalDrivers, etc.).
     */
    private function carrierBody(): array
    {
        return [
            'content' => [
                'carrier' => [
                    'dotNumber' => 123456,
                    'legalName' => 'FREIGHTLINER TRUCKING LLC',
                    'dbaName' => 'FL EXPRESS',
                    'phone' => '5551234567',
                    'phyStreet' => '100 MAIN ST',
                    'phyCity' => 'PORTLAND',
                    'phyState' => 'OR',
                    'phyZipcode' => '97201',
                    'phyCountry' => 'US',
                    'totalDrivers' => 42,
                    'totalPowerUnits' => 17,
                    'safetyRating' => 'S',
                    'allowedToOperate' => 'Y',
                    'oosDate' => null,
                    'mcNumber' => 'MC-987654',
                ],
            ],
        ];
    }

    public function test_snapshot_maps_carrier_fields_and_attaches_web_key(): void
    {
        config()->set('services.fmcsa.web_key', 'test-web-key');

        Http::fake([
            'mobile.fmcsa.dot.gov/*' => Http::response($this->carrierBody(), 200),
        ]);

        $service = app(CarrierService::class);

        $result = $service->snapshot('123456');

        $this->assertFalse($result['error'] ?? false);
        $this->assertSame('123456', $result['usdot']);
        $this->assertSame('FREIGHTLINER TRUCKING LLC', $result['legal_name']);
        $this->assertSame('FL EXPRESS', $result['dba_name']);
        $this->assertSame('5551234567', $result['phone']);
        $this->assertSame('100 MAIN ST, PORTLAND, OR, 97201, US', $result['physical_address']);
        $this->assertSame(42, $result['total_drivers']);
        $this->assertSame(17, $result['total_power_units']);
        $this->assertSame('S', $result['safety_rating']);
        $this->assertTrue($result['allowed_to_operate']);
        $this->assertSame('MC-987654', $result['mc_number']);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'carriers/123456')
                && str_contains($request->url(), 'webKey=test-web-key');
        });
    }

    public function test_snapshot_returns_503_when_web_key_missing(): void
    {
        config()->set('services.fmcsa.web_key', '');

        Http::fake([
            'mobile.fmcsa.dot.gov/*' => Http::response($this->carrierBody(), 200),
        ]);

        $service = app(CarrierService::class);

        $result = $service->snapshot('123456');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_snapshot_passes_through_upstream_404(): void
    {
        config()->set('services.fmcsa.web_key', 'test-web-key');

        Http::fake([
            'mobile.fmcsa.dot.gov/*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $service = app(CarrierService::class);

        $result = $service->snapshot('999999');

        $this->assertTrue($result['error']);
        $this->assertSame(404, $result['status']);
    }
}
