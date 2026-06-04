<?php

namespace Tests\Unit\Integrations;

use App\Services\Document\ESignatureService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Covers the DocuSign eSignature integration end to end against dummy data:
 * ESignatureService over the DocusignApi client. All HTTP is faked (Http::fake)
 * — no real network calls and no database.
 *
 * DocusignApi reads services.docusign.base_uri / account_id / access_token in its
 * CONSTRUCTOR, assembling the per-account base URL
 * "{base_uri}/restapi/v2.1/accounts/{account_id}/" and attaching the OAuth token
 * via ->withToken() (Bearer). So every test sets config BEFORE resolving the
 * service via app(ESignatureService::class).
 */
class EsignatureTest extends TestCase
{
    private function configureCreds(
        string $baseUri = 'https://demo.docusign.net',
        string $accountId = 'acc1',
        string $accessToken = 'tok'
    ): void {
        config()->set('services.docusign.base_uri', $baseUri);
        config()->set('services.docusign.account_id', $accountId);
        config()->set('services.docusign.access_token', $accessToken);
    }

    public function test_send_for_signature_maps_response_and_posts_envelope_with_bearer(): void
    {
        $this->configureCreds();

        Http::fake([
            'demo.docusign.net/*' => Http::response([
                'envelopeId' => 'env1',
                'status' => 'sent',
                'statusDateTime' => '2026-06-04T00:00:00.0000000Z',
                'uri' => '/envelopes/env1',
            ], 201),
        ]);

        $service = app(ESignatureService::class);

        $result = $service->sendForSignature(
            'driver@example.com',
            'Jane Driver',
            'Please sign your lease agreement',
            base64_encode('PDF-BYTES'),
            'lease.pdf'
        );

        // Mapping: clean domain array with concrete values from the dummy body.
        $this->assertTrue($result['success']);
        $this->assertSame('env1', $result['envelope_id']);
        $this->assertSame('sent', $result['status']);

        // Request shape: POST to the per-account envelopes endpoint with a Bearer
        // token (DocuSign withToken auth) and the envelope definition in the body.
        Http::assertSent(function ($request) {
            $body = $request->data();

            return $request->method() === 'POST'
                && str_contains($request->url(), '/restapi/v2.1/accounts/acc1/envelopes')
                && $request->hasHeader('Authorization', 'Bearer tok')
                && ($body['emailSubject'] ?? null) === 'Please sign your lease agreement'
                && ($body['status'] ?? null) === 'sent'
                && ($body['documents'][0]['name'] ?? null) === 'lease.pdf'
                && ($body['documents'][0]['fileExtension'] ?? null) === 'pdf'
                && ($body['recipients']['signers'][0]['email'] ?? null) === 'driver@example.com'
                && ($body['recipients']['signers'][0]['name'] ?? null) === 'Jane Driver';
        });
    }

    public function test_status_maps_envelope_state_from_get(): void
    {
        $this->configureCreds();

        Http::fake([
            'demo.docusign.net/*' => Http::response([
                'envelopeId' => 'env1',
                'status' => 'completed',
                'sentDateTime' => '2026-06-01T10:00:00.0000000Z',
                'completedDateTime' => '2026-06-02T12:30:00.0000000Z',
            ], 200),
        ]);

        $service = app(ESignatureService::class);

        $result = $service->status('env1');

        $this->assertSame('env1', $result['envelope_id']);
        $this->assertSame('completed', $result['status']);
        $this->assertSame('2026-06-01T10:00:00.0000000Z', $result['sent_at']);
        $this->assertSame('2026-06-02T12:30:00.0000000Z', $result['completed_at']);
        $this->assertSame('completed', $result['raw']['status']);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET'
                && str_contains($request->url(), '/restapi/v2.1/accounts/acc1/envelopes/env1')
                && $request->hasHeader('Authorization', 'Bearer tok');
        });
    }

    public function test_send_for_signature_not_configured_returns_503_and_sends_nothing(): void
    {
        // Empty account_id and access_token => DocusignApi::isConfigured() is false.
        $this->configureCreds('https://demo.docusign.net', '', '');

        Http::fake([
            'demo.docusign.net/*' => Http::response([
                'envelopeId' => 'should_not_happen',
                'status' => 'sent',
            ], 201),
        ]);

        $service = app(ESignatureService::class);

        $result = $service->sendForSignature(
            'driver@example.com',
            'Jane Driver',
            'Subject',
            base64_encode('PDF-BYTES'),
            'lease.pdf'
        );

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);
        $this->assertArrayNotHasKey('success', $result);

        Http::assertNothingSent();
    }

    public function test_send_for_signature_error_pass_through_propagates_status(): void
    {
        $this->configureCreds();

        Http::fake([
            'demo.docusign.net/*' => Http::response([
                'errorCode' => 'USER_AUTHENTICATION_FAILED',
                'message' => 'The access token provided has expired.',
            ], 401),
        ]);

        $service = app(ESignatureService::class);

        $result = $service->sendForSignature(
            'driver@example.com',
            'Jane Driver',
            'Subject',
            base64_encode('PDF-BYTES'),
            'lease.pdf'
        );

        $this->assertTrue($result['error']);
        $this->assertSame(401, $result['status']);
        $this->assertArrayNotHasKey('success', $result);
    }

    public function test_status_error_pass_through_propagates_status(): void
    {
        $this->configureCreds();

        Http::fake([
            'demo.docusign.net/*' => Http::response([
                'errorCode' => 'ENVELOPE_DOES_NOT_EXIST',
                'message' => 'The envelope could not be found.',
            ], 404),
        ]);

        $service = app(ESignatureService::class);

        $result = $service->status('missing-envelope');

        $this->assertTrue($result['error']);
        $this->assertSame(404, $result['status']);
        $this->assertArrayNotHasKey('envelope_id', $result);
    }
}
