<?php

namespace Tests\Unit\Integrations;

use App\Services\Driver\PspService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the FMCSA PSP integration (PspService -> PspApi).
 *
 * No database, no real network: every call is stubbed via Http::fake(). Credentials
 * are read in PspApi's constructor, so each test configures services.psp.* BEFORE
 * resolving the service from the container.
 */
class PspServiceTest extends TestCase
{
    private function configure(
        string $baseUrl = 'https://psp.example.test/',
        string $apiKey = 'k',
        string $accountId = 'acct-9'
    ): void {
        config()->set('services.psp.base_url', $baseUrl);
        config()->set('services.psp.api_key', $apiKey);
        config()->set('services.psp.account_id', $accountId);
    }

    private function driver(): array
    {
        return [
            'first_name' => 'JOHN',
            'last_name' => 'DRIVER',
            'license_number' => 'D1234567',
            'license_state' => 'CA',
        ];
    }

    public function test_order_maps_success_and_sends_post_with_bearer_and_account(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'report_id' => 'rp1',
                'status' => 'pending',
            ], 200),
        ]);

        $service = app(PspService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['success']);
        $this->assertSame('rp1', $result['report_id']);
        $this->assertSame('pending', $result['status']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'reports')
                && $request->hasHeader('Authorization', 'Bearer k')
                // Service always forces the captured-consent flag.
                && $request['consent'] === true
                && $request['license_number'] === 'D1234567'
                // Client attaches the carrier account id to the order payload.
                && $request['account_id'] === 'acct-9';
        });
    }

    public function test_fetch_maps_crashes_inspections_and_violations(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'status' => 'complete',
                'crashes' => [
                    [
                        'report_number' => 'C-100',
                        'crash_date' => '2024-03-02',
                        'state' => 'TX',
                        'fatalities' => 0,
                        'injuries' => 2,
                        'tow_away' => true,
                    ],
                ],
                'inspections' => [
                    [
                        'report_number' => 'I-200',
                        'inspection_date' => '2024-06-15',
                        'state' => 'NV',
                        'level' => 'I',
                        'violations' => [
                            [
                                'code' => '392.2',
                                'description' => 'Speeding',
                                'oos' => false,
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = app(PspService::class);

        $result = $service->fetch('rp1');

        $this->assertSame('complete', $result['status']);

        $this->assertCount(1, $result['crashes']);
        $this->assertSame('C-100', $result['crashes'][0]['report_number']);
        $this->assertSame('2024-03-02', $result['crashes'][0]['crash_date']);
        $this->assertSame('TX', $result['crashes'][0]['state']);
        $this->assertSame(0, $result['crashes'][0]['fatalities']);
        $this->assertSame(2, $result['crashes'][0]['injuries']);
        $this->assertTrue($result['crashes'][0]['tow_away']);

        $this->assertCount(1, $result['inspections']);
        $this->assertSame('I-200', $result['inspections'][0]['report_number']);
        $this->assertSame('2024-06-15', $result['inspections'][0]['inspection_date']);
        $this->assertSame('NV', $result['inspections'][0]['state']);
        $this->assertSame('I', $result['inspections'][0]['level']);

        $this->assertCount(1, $result['inspections'][0]['violations']);
        $this->assertSame('392.2', $result['inspections'][0]['violations'][0]['code']);
        $this->assertSame('Speeding', $result['inspections'][0]['violations'][0]['description']);
        $this->assertFalse($result['inspections'][0]['violations'][0]['oos']);

        // raw is the untouched provider body.
        $this->assertSame('complete', $result['raw']['status']);
    }

    public function test_fetch_maps_alternate_field_names(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'status' => 'complete',
                'crash_history' => [
                    [
                        'crash_report_number' => 'C-ALT',
                        'date' => '2023-11-01',
                        'report_state' => 'AZ',
                        'number_of_fatalities' => 1,
                        'number_of_injuries' => 0,
                        'towaway' => false,
                    ],
                ],
                'inspection_history' => [
                    [
                        'inspection_report_number' => 'I-ALT',
                        'date' => '2023-12-09',
                        'report_state' => 'CO',
                        'inspection_level' => 'II',
                        'violations' => [
                            [
                                'violation_code' => '393.9',
                                'desc' => 'Inoperative lamp',
                                'out_of_service' => true,
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = app(PspService::class);

        $result = $service->fetch('rp1');

        $this->assertSame('C-ALT', $result['crashes'][0]['report_number']);
        $this->assertSame('2023-11-01', $result['crashes'][0]['crash_date']);
        $this->assertSame('AZ', $result['crashes'][0]['state']);
        $this->assertSame(1, $result['crashes'][0]['fatalities']);
        $this->assertFalse($result['crashes'][0]['tow_away']);

        $this->assertSame('I-ALT', $result['inspections'][0]['report_number']);
        $this->assertSame('2023-12-09', $result['inspections'][0]['inspection_date']);
        $this->assertSame('CO', $result['inspections'][0]['state']);
        $this->assertSame('II', $result['inspections'][0]['level']);
        $this->assertSame('393.9', $result['inspections'][0]['violations'][0]['code']);
        $this->assertSame('Inoperative lamp', $result['inspections'][0]['violations'][0]['description']);
        $this->assertTrue($result['inspections'][0]['violations'][0]['oos']);
    }

    public function test_fetch_sends_get_to_report_endpoint_with_bearer_and_account(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'status' => 'pending',
            ], 200),
        ]);

        $service = app(PspService::class);

        $service->fetch('rp1');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'reports/rp1')
                && $request->hasHeader('Authorization', 'Bearer k')
                // Client attaches the carrier account id as a query parameter.
                && str_contains($request->url(), 'account_id=acct-9');
        });
    }

    public function test_order_returns_503_when_not_configured(): void
    {
        // Empty credentials -> PspApi::isConfigured() is false -> clean 503, no call.
        $this->configure('', '');

        Http::fake([
            'psp.example.test/*' => Http::response([], 200),
        ]);

        $service = app(PspService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_fetch_returns_503_when_not_configured(): void
    {
        $this->configure('', '');

        Http::fake([
            'psp.example.test/*' => Http::response([], 200),
        ]);

        $service = app(PspService::class);

        $result = $service->fetch('rp1');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_order_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'message' => 'Upstream PSP failure',
            ], 500),
        ]);

        $service = app(PspService::class);

        $result = $service->order($this->driver());

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_fetch_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'psp.example.test/*' => Http::response([
                'message' => 'Report not found',
            ], 422),
        ]);

        $service = app(PspService::class);

        $result = $service->fetch('missing');

        $this->assertTrue($result['error']);
        $this->assertSame(422, $result['status']);
    }
}
