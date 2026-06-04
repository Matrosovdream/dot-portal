<?php

namespace Tests\Unit\Integrations;

use App\Services\Document\DocumentOcrService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Unit coverage for the Document OCR integration (DocumentOcrService → DocumentOcrApi).
 *
 * No database, no real network: every call is stubbed via Http::fake(). Credentials
 * are read in DocumentOcrApi's constructor, so each test configures services.ocr.*
 * BEFORE resolving the service from the container.
 */
class DocumentOcrTest extends TestCase
{
    private function configure(
        string $baseUrl = 'https://ocr.example.test/',
        string $apiKey = 'k',
        string $provider = 'textract'
    ): void {
        config()->set('services.ocr.base_url', $baseUrl);
        config()->set('services.ocr.api_key', $apiKey);
        config()->set('services.ocr.provider', $provider);
    }

    public function test_extract_cdl_maps_fields_envelope_and_sends_post_with_bearer_auth(): void
    {
        $this->configure();

        // Provider nests parsed key/value pairs under a "fields" envelope. Mixes
        // plain-string values with the {"value": ...} confidence-object form and
        // relies on an alias key (issuing_state) for the state, so the service's
        // fields()/value() readers must unwrap both shapes.
        Http::fake([
            'ocr.example.test/*' => Http::response([
                'fields' => [
                    'license_number' => 'D1234567',
                    'issuing_state' => ['value' => 'CA', 'confidence' => 0.99],
                    'expiration_date' => '2028-05-04',
                    'class' => 'A',
                ],
            ], 200),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractCdl('https://files.example.test/cdl.jpg');

        $this->assertSame('D1234567', $result['license_number']);
        $this->assertSame('CA', $result['license_state']);
        $this->assertSame('2028-05-04', $result['expiration_date']);
        $this->assertSame('A', $result['class']);
        // raw carries the untouched provider body.
        $this->assertSame('D1234567', $result['raw']['fields']['license_number']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'analyze')
                && $request->hasHeader('Authorization', 'Bearer k')
                && $request['document_type'] === 'cdl'
                && $request['document_url'] === 'https://files.example.test/cdl.jpg'
                && $request['provider'] === 'textract';
        });
    }

    public function test_extract_medical_card_maps_aliases_and_sends_post_with_bearer_auth(): void
    {
        $this->configure();

        // Uses the "data" envelope and alias field names (medical_examiner,
        // national_registry_number, examination_date) to confirm the candidate-key
        // fallback ordering in value().
        Http::fake([
            'ocr.example.test/*' => Http::response([
                'data' => [
                    'medical_examiner' => 'DR JANE SMITH',
                    'national_registry_number' => '1234567890',
                    'examination_date' => '2025-01-15',
                    'expiration_date' => ['value' => '2027-01-15'],
                ],
            ], 200),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractMedicalCard('https://files.example.test/med.jpg');

        $this->assertSame('DR JANE SMITH', $result['examiner_name']);
        $this->assertSame('1234567890', $result['national_registry']);
        $this->assertSame('2025-01-15', $result['issue_date']);
        $this->assertSame('2027-01-15', $result['expiration_date']);
        $this->assertSame('DR JANE SMITH', $result['raw']['data']['medical_examiner']);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'analyze')
                && $request->hasHeader('Authorization', 'Bearer k')
                && $request['document_type'] === 'medical_card'
                && $request['document_url'] === 'https://files.example.test/med.jpg';
        });
    }

    public function test_extract_cdl_returns_503_when_not_configured(): void
    {
        // Empty credentials → DocumentOcrApi::isConfigured() is false → clean 503, no call.
        $this->configure('', '');

        Http::fake([
            'ocr.example.test/*' => Http::response([], 200),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractCdl('https://files.example.test/cdl.jpg');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_extract_medical_card_returns_503_when_not_configured(): void
    {
        $this->configure('', '');

        Http::fake([
            'ocr.example.test/*' => Http::response([], 200),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractMedicalCard('https://files.example.test/med.jpg');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_extract_cdl_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'ocr.example.test/*' => Http::response([
                'message' => 'OCR backend unavailable',
            ], 500),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractCdl('https://files.example.test/cdl.jpg');

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
    }

    public function test_extract_medical_card_passes_through_provider_error_status(): void
    {
        $this->configure();

        Http::fake([
            'ocr.example.test/*' => Http::response([
                'message' => 'Unprocessable document',
            ], 422),
        ]);

        $service = app(DocumentOcrService::class);

        $result = $service->extractMedicalCard('https://files.example.test/med.jpg');

        $this->assertTrue($result['error']);
        $this->assertSame(422, $result['status']);
    }
}
