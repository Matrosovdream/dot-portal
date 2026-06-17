<?php

namespace Tests\Unit\Integrations;

use App\Services\Address\AddressVerificationService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the Smarty US Street Address integration.
 *
 * Drives App\Services\Address\AddressVerificationService (which wraps the SmartyApi
 * transport client) with Http::fake() dummy candidate bodies — no real network
 * calls and no database. Credentials are injected per-test via config() BEFORE the
 * service is resolved, because SmartyApi reads them in its constructor.
 */
class SmartyAddressTest extends TestCase
{
    /**
     * A representative Smarty candidate response: a flat JSON array of candidate
     * objects. Field names match exactly what AddressVerificationService reads
     * (delivery_line_1, components.*, analysis.dpv_match_code).
     */
    private function dummyCandidates(string $dpvMatchCode = 'Y'): array
    {
        return [
            [
                'input_index' => 0,
                'candidate_index' => 0,
                'delivery_line_1' => '1600 Amphitheatre Pkwy',
                'last_line' => 'Mountain View CA 94043-1351',
                'components' => [
                    'primary_number' => '1600',
                    'street_name' => 'Amphitheatre',
                    'street_suffix' => 'Pkwy',
                    'city_name' => 'Mountain View',
                    'state_abbreviation' => 'CA',
                    'zipcode' => '94043',
                    'plus4_code' => '1351',
                ],
                'analysis' => [
                    'dpv_match_code' => $dpvMatchCode,
                    'dpv_footnotes' => 'AABB',
                    'active' => 'Y',
                ],
            ],
        ];
    }

    private function configureCredentials(): void
    {
        config()->set('services.smarty.auth_id', 'test-auth-id');
        config()->set('services.smarty.auth_token', 'test-auth-token');
    }

    private function sampleAddress(): array
    {
        return [
            'street' => '1600 Amphitheatre Pkwy',
            'city' => 'Mountain View',
            'state' => 'CA',
            'zipcode' => '94043',
        ];
    }

    public function test_verify_maps_candidate_and_marks_address_valid(): void
    {
        $this->configureCredentials();

        Http::fake([
            'us-street.api.smarty.com/*' => Http::response($this->dummyCandidates('Y'), 200),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertTrue($result['valid']);
        $this->assertSame('Y', $result['dpv_match_code']);

        $this->assertSame([
            'line1' => '1600 Amphitheatre Pkwy',
            'city' => 'Mountain View',
            'state' => 'CA',
            'zipcode' => '94043',
            'plus4' => '1351',
        ], $result['normalized']);

        // The raw provider body is passed straight through.
        $this->assertSame('AABB', $result['raw'][0]['analysis']['dpv_footnotes']);
    }

    public function test_verify_sends_get_to_street_address_with_query_auth(): void
    {
        $this->configureCredentials();

        Http::fake([
            'us-street.api.smarty.com/*' => Http::response($this->dummyCandidates('Y'), 200),
        ]);

        $service = app(AddressVerificationService::class);

        $service->verify($this->sampleAddress());

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'us-street.api.smarty.com/street-address')
                // Smarty authenticates via query-string credentials, not headers.
                && $request['auth-id'] === 'test-auth-id'
                && $request['auth-token'] === 'test-auth-token'
                && $request['street'] === '1600 Amphitheatre Pkwy'
                && $request['city'] === 'Mountain View'
                && (string) $request['candidates'] === '1';
        });
    }

    public function test_verify_marks_non_confirmed_dpv_code_invalid(): void
    {
        $this->configureCredentials();

        Http::fake([
            'us-street.api.smarty.com/*' => Http::response($this->dummyCandidates('N'), 200),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertFalse($result['valid']);
        $this->assertSame('N', $result['dpv_match_code']);
    }

    public function test_verify_returns_503_error_when_not_configured(): void
    {
        config()->set('services.smarty.auth_id', '');
        config()->set('services.smarty.auth_token', '');

        Http::fake([
            'us-street.api.smarty.com/*' => Http::response($this->dummyCandidates('Y'), 200),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        // Unconfigured clients must never reach the wire.
        Http::assertNothingSent();
    }

    public function test_verify_passes_through_upstream_error_status(): void
    {
        $this->configureCredentials();

        Http::fake([
            'us-street.api.smarty.com/*' => Http::response(['error' => 'boom'], 500),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_verify_treats_empty_candidate_list_as_invalid(): void
    {
        $this->configureCredentials();

        // Smarty returns a 200 with an empty array when no address matches —
        // a success response, not an error. It must map to an invalid result.
        Http::fake([
            'us-street.api.smarty.com/*' => Http::response([], 200),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertFalse($result['valid']);
        $this->assertNull($result['dpv_match_code']);
        $this->assertSame([
            'line1' => null,
            'city' => null,
            'state' => null,
            'zipcode' => null,
            'plus4' => null,
        ], $result['normalized']);
        $this->assertSame([], $result['raw']);
    }

    public function test_verify_handles_candidate_missing_components_and_analysis(): void
    {
        $this->configureCredentials();

        // A candidate carrying only delivery_line_1 must not break the
        // components/analysis guards: line1 maps through, the rest fall to null.
        Http::fake([
            'us-street.api.smarty.com/*' => Http::response([
                ['delivery_line_1' => '1600 Amphitheatre Pkwy'],
            ], 200),
        ]);

        $service = app(AddressVerificationService::class);

        $result = $service->verify($this->sampleAddress());

        $this->assertFalse($result['valid']);
        $this->assertNull($result['dpv_match_code']);
        $this->assertSame([
            'line1' => '1600 Amphitheatre Pkwy',
            'city' => null,
            'state' => null,
            'zipcode' => null,
            'plus4' => null,
        ], $result['normalized']);
    }
}
