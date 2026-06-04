<?php

namespace Tests\Unit\Integrations;

use App\Services\Vehicle\RecallService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit tests for the NHTSA Safety integration (recalls + complaints).
 *
 * NhtsaSafetyAPI is a public, KEYLESS government client (api.nhtsa.gov), so there
 * is no credential / not-configured case. All HTTP is faked via Http::fake — no
 * real network calls and no database are involved.
 */
class NhtsaSafetyTest extends TestCase
{
    private function recallsBody(): array
    {
        return [
            'Count' => 1,
            'results' => [
                [
                    'NHTSACampaignNumber' => '23V456000',
                    'Component' => 'POWER TRAIN',
                    'Summary' => 'Transmission may fail unexpectedly.',
                    'Consequence' => 'Loss of motive power increases crash risk.',
                    'Remedy' => 'Dealers will replace the transmission free of charge.',
                    'ReportReceivedDate' => '12/01/2023',
                ],
            ],
        ];
    }

    private function complaintsBody(): array
    {
        return [
            'count' => 1,
            'results' => [
                [
                    'odiNumber' => 11567890,
                    'components' => 'ENGINE',
                    'summary' => 'Engine stalls while driving.',
                    'crash' => false,
                    'fire' => false,
                    'numberOfInjuries' => 2,
                    'numberOfDeaths' => 0,
                    'dateComplaintFiled' => '11/15/2023',
                ],
            ],
        ];
    }

    public function test_for_vehicle_maps_recalls_and_sends_correct_request(): void
    {
        Http::fake([
            'api.nhtsa.gov/*' => Http::response($this->recallsBody(), 200),
        ]);

        $service = app(RecallService::class);

        $result = $service->forVehicle('FREIGHTLINER', 'CASCADIA', 2023);

        $this->assertSame(1, $result['count']);
        $this->assertCount(1, $result['recalls']);

        $recall = $result['recalls'][0];
        $this->assertSame('23V456000', $recall['campaign_number']);
        $this->assertSame('POWER TRAIN', $recall['component']);
        $this->assertSame('Transmission may fail unexpectedly.', $recall['summary']);
        $this->assertSame('Loss of motive power increases crash risk.', $recall['consequence']);
        $this->assertSame('Dealers will replace the transmission free of charge.', $recall['remedy']);
        $this->assertSame('12/01/2023', $recall['report_received_date']);

        // Request shape: GET to recalls/recallsByVehicle with the query params.
        // Keyless client — no auth header is attached.
        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'recalls/recallsByVehicle')
                && ! $request->hasHeader('Authorization')
                && $request['make'] === 'FREIGHTLINER'
                && $request['model'] === 'CASCADIA'
                && $request['modelYear'] === 2023;
        });
    }

    public function test_complaints_maps_results_and_sends_correct_request(): void
    {
        Http::fake([
            'api.nhtsa.gov/*' => Http::response($this->complaintsBody(), 200),
        ]);

        $service = app(RecallService::class);

        $result = $service->complaints('FREIGHTLINER', 'CASCADIA', 2023);

        $this->assertSame(1, $result['count']);
        $this->assertCount(1, $result['complaints']);

        $complaint = $result['complaints'][0];
        $this->assertSame(11567890, $complaint['odi_number']);
        $this->assertSame('ENGINE', $complaint['component']);
        $this->assertSame('Engine stalls while driving.', $complaint['summary']);
        $this->assertFalse($complaint['crash']);
        $this->assertFalse($complaint['fire']);
        $this->assertSame(2, $complaint['number_of_injuries']);
        $this->assertSame(0, $complaint['number_of_deaths']);
        $this->assertSame('11/15/2023', $complaint['date_complaint_filed']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'complaints/complaintsByVehicle')
                && ! $request->hasHeader('Authorization')
                && $request['make'] === 'FREIGHTLINER'
                && $request['model'] === 'CASCADIA'
                && $request['modelYear'] === 2023;
        });
    }

    public function test_for_vehicle_returns_empty_recalls_when_no_results(): void
    {
        Http::fake([
            'api.nhtsa.gov/*' => Http::response(['Count' => 0, 'results' => []], 200),
        ]);

        $service = app(RecallService::class);

        $result = $service->forVehicle('FREIGHTLINER', 'CASCADIA', 2023);

        $this->assertSame(0, $result['count']);
        $this->assertSame([], $result['recalls']);
    }

    public function test_for_vehicle_passes_through_error_response(): void
    {
        Http::fake([
            'api.nhtsa.gov/*' => Http::response(['message' => 'Internal Server Error'], 500),
        ]);

        $service = app(RecallService::class);

        $result = $service->forVehicle('FREIGHTLINER', 'CASCADIA', 2023);

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_complaints_passes_through_error_response(): void
    {
        Http::fake([
            'api.nhtsa.gov/*' => Http::response('rate limited', 429),
        ]);

        $service = app(RecallService::class);

        $result = $service->complaints('FREIGHTLINER', 'CASCADIA', 2023);

        $this->assertTrue($result['error']);
        $this->assertSame(429, $result['status']);
    }
}
