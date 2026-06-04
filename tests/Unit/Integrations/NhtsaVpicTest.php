<?php

namespace Tests\Unit\Integrations;

use App\Services\Vehicle\VinDecodeService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Integration test for the NHTSA vPIC VIN decoder.
 *
 * vPIC is a public, keyless US DOT / NHTSA service, so there is no credential to
 * configure and no not-configured case to assert: the client never overrides
 * isConfigured(). All transport is stubbed with Http::fake() — no real network
 * calls are made. We assert the snake_case mapping produced by
 * VinDecodeService::mapDecoded()/clean(), the GET request shape (endpoint path +
 * format=json query), and that HTTP error statuses pass through as the
 * normalised error array.
 */
class NhtsaVpicTest extends TestCase
{
    /**
     * A representative vPIC DecodeVinValues response body (Count + Results[0]),
     * matching the real provider shape and the exact field names the service reads.
     */
    private function vpicBody(array $overrides = []): array
    {
        $row = array_merge([
            'VIN' => '1FUJGLDR9CSBP8675',
            'Make' => 'FREIGHTLINER',
            'Model' => 'Cascadia',
            'ModelYear' => '2012',
            'Manufacturer' => 'DAIMLER TRUCKS NORTH AMERICA LLC',
            'VehicleType' => 'TRUCK',
            'BodyClass' => 'Truck-Tractor',
            'GVWR' => 'Class 8: 33,001 lb and above (14,969 kg and above)',
            'FuelTypePrimary' => 'Diesel',
            'EngineCylinders' => '6',
            'DisplacementL' => '14.8',
            'PlantCountry' => 'UNITED STATES (USA)',
            'ErrorCode' => '0',
            'ErrorText' => '0 - VIN decoded clean. Check Digit (9th position) is correct',
        ], $overrides);

        return [
            'Count' => 1,
            'Message' => 'Results returned successfully',
            'SearchCriteria' => 'VIN:' . $row['VIN'],
            'Results' => [$row],
        ];
    }

    public function test_decode_maps_results_to_snake_case_domain_array(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response($this->vpicBody(), 200),
        ]);

        $service = app(VinDecodeService::class);

        $result = $service->decode('1FUJGLDR9CSBP8675');

        $this->assertFalse($result['error'] ?? false);

        // Mapped snake_case keys produced by mapDecoded().
        $this->assertArrayHasKey('vin', $result);
        $this->assertArrayHasKey('make', $result);
        $this->assertArrayHasKey('model', $result);
        $this->assertArrayHasKey('model_year', $result);
        $this->assertArrayHasKey('vehicle_type', $result);
        $this->assertArrayHasKey('body_class', $result);
        $this->assertArrayHasKey('gvwr', $result);
        $this->assertArrayHasKey('fuel_type_primary', $result);
        $this->assertArrayHasKey('error_code', $result);
        $this->assertArrayHasKey('raw', $result);

        // Concrete values derived from the dummy body.
        $this->assertSame('1FUJGLDR9CSBP8675', $result['vin']);
        $this->assertSame('FREIGHTLINER', $result['make']);
        $this->assertSame('Cascadia', $result['model']);
        $this->assertSame('2012', $result['model_year']);
        $this->assertSame('TRUCK', $result['vehicle_type']);
        $this->assertSame('Truck-Tractor', $result['body_class']);
        $this->assertSame('Diesel', $result['fuel_type_primary']);
        $this->assertSame('0', $result['error_code']);

        // The original Results[0] map is preserved verbatim under 'raw'.
        $this->assertIsArray($result['raw']);
        $this->assertSame('FREIGHTLINER', $result['raw']['Make']);
    }

    public function test_decode_sends_get_to_decode_vin_values_with_format_json(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response($this->vpicBody(), 200),
        ]);

        $service = app(VinDecodeService::class);

        $service->decode('1FUJGLDR9CSBP8675');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/1FUJGLDR9CSBP8675')
                && str_contains($request->url(), 'format=json')
                // Keyless service: no model year unless supplied.
                && ! str_contains($request->url(), 'modelyear');
        });
    }

    public function test_decode_with_year_attaches_modelyear_query(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response($this->vpicBody(), 200),
        ]);

        $service = app(VinDecodeService::class);

        $service->decode('1FUJGLDR9CSBP8675', 2012);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), 'vehicles/DecodeVinValues/1FUJGLDR9CSBP8675')
                && str_contains($request->url(), 'format=json')
                && str_contains($request->url(), 'modelyear=2012');
        });
    }

    public function test_decode_cleans_empty_strings_to_null(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response($this->vpicBody([
                'Model' => '',
                'BodyClass' => '  ',
            ]), 200),
        ]);

        $service = app(VinDecodeService::class);

        $result = $service->decode('1FUJGLDR9CSBP8675');

        // clean() normalises empty / whitespace-only strings to null.
        $this->assertNull($result['model']);
        $this->assertNull($result['body_class']);
        // Untouched fields still map.
        $this->assertSame('FREIGHTLINER', $result['make']);
    }

    public function test_decode_falls_back_to_input_vin_when_results_missing(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response(['Count' => 0, 'Results' => []], 200),
        ]);

        $service = app(VinDecodeService::class);

        $result = $service->decode('1FUJGLDR9CSBP8675');

        // No Results row: VIN falls back to the caller's input, rest are null.
        $this->assertSame('1FUJGLDR9CSBP8675', $result['vin']);
        $this->assertNull($result['make']);
        $this->assertSame([], $result['raw']);
    }

    public function test_decode_passes_through_http_error_status(): void
    {
        Http::fake([
            'vpic.nhtsa.dot.gov/*' => Http::response(['message' => 'Bad Gateway'], 500),
        ]);

        $service = app(VinDecodeService::class);

        $result = $service->decode('1FUJGLDR9CSBP8675');

        // The integration error array is passed straight through by the service.
        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
        $this->assertArrayNotHasKey('make', $result);
    }
}
