<?php

namespace Tests\Unit\Integrations;

use App\Services\Payments\BankVerificationService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Covers the Plaid bank-verification integration end to end against dummy data.
 *
 * The PlaidApi client reads services.plaid.* in its constructor and sends
 * client_id + secret in the JSON BODY of every call, so each test configures
 * the credentials and fakes the HTTP layer BEFORE resolving the service.
 */
class PlaidBankTest extends TestCase
{
    private function configureCreds(string $clientId = 'test-client-id', string $secret = 'test-secret', string $env = 'sandbox'): void
    {
        config()->set('services.plaid.client_id', $clientId);
        config()->set('services.plaid.secret', $secret);
        config()->set('services.plaid.env', $env);
    }

    public function test_exchange_maps_success_response_and_sends_authenticated_request(): void
    {
        $this->configureCreds();

        Http::fake([
            'sandbox.plaid.com/*' => Http::response([
                'access_token' => 'access-sandbox-1',
                'item_id' => 'item_1',
                'request_id' => 'r1',
            ], 200),
        ]);

        $service = app(BankVerificationService::class);

        $result = $service->exchange('public-sandbox-abc');

        // Mapping: clean snake_case domain array with concrete values from the dummy body.
        $this->assertTrue($result['success']);
        $this->assertSame('access-sandbox-1', $result['access_token']);
        $this->assertSame('item_1', $result['item_id']);

        // Request shape: POST to the exchange endpoint on the sandbox host, with
        // client_id + secret + public_token in the JSON body (Plaid auth style).
        Http::assertSent(function ($request) {
            $body = $request->data();

            return $request->method() === 'POST'
                && str_contains($request->url(), 'sandbox.plaid.com/item/public_token/exchange')
                && ($body['client_id'] ?? null) === 'test-client-id'
                && ($body['secret'] ?? null) === 'test-secret'
                && ($body['public_token'] ?? null) === 'public-sandbox-abc';
        });
    }

    public function test_create_link_token_posts_to_link_endpoint_with_credentials(): void
    {
        $this->configureCreds();

        Http::fake([
            'sandbox.plaid.com/*' => Http::response([
                'link_token' => 'link-sandbox-1',
                'expiration' => '2026-06-04T00:00:00Z',
                'request_id' => 'r2',
            ], 200),
        ]);

        $service = app(BankVerificationService::class);

        $result = $service->createLinkToken('user-42');

        $this->assertSame('link-sandbox-1', $result['link_token']);

        Http::assertSent(function ($request) {
            $body = $request->data();

            return $request->method() === 'POST'
                && str_contains($request->url(), 'sandbox.plaid.com/link/token/create')
                && ($body['client_id'] ?? null) === 'test-client-id'
                && ($body['secret'] ?? null) === 'test-secret'
                && ($body['user']['client_user_id'] ?? null) === 'user-42';
        });
    }

    public function test_exchange_returns_503_error_when_not_configured(): void
    {
        $this->configureCreds('', '', 'sandbox');

        Http::fake([
            'sandbox.plaid.com/*' => Http::response([], 200),
        ]);

        $service = app(BankVerificationService::class);

        $result = $service->exchange('public-sandbox-abc');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_exchange_passes_through_error_status_from_plaid(): void
    {
        $this->configureCreds();

        Http::fake([
            'sandbox.plaid.com/*' => Http::response([
                'error_type' => 'INVALID_INPUT',
                'error_code' => 'INVALID_PUBLIC_TOKEN',
                'message' => 'provided public token is invalid',
            ], 400),
        ]);

        $service = app(BankVerificationService::class);

        $result = $service->exchange('bad-token');

        $this->assertTrue($result['error']);
        $this->assertSame(400, $result['status']);
    }
}
