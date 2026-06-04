<?php

namespace Tests\Unit\Integrations;

use App\Services\Payments\StripePaymentService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Covers the Stripe gateway transport + mapping: StripePaymentService over the
 * StripeApi client. All HTTP is faked (Http::fake) — no real network calls and
 * no database. The Stripe secret lives in config('services.stripe.secret') and
 * is read in StripeApi's constructor, so every test sets config BEFORE resolving
 * the service via app(StripePaymentService::class).
 */
class StripePaymentTest extends TestCase
{
    public function test_create_customer_success_maps_response_and_sends_bearer_form_post(): void
    {
        config()->set('services.stripe.secret', 'sk_test_dummy');

        $body = [
            'id' => 'cus_123',
            'object' => 'customer',
            'email' => 'a@b.com',
            'name' => 'Acme Co',
        ];

        Http::fake([
            'api.stripe.com/*' => Http::response($body, 200),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->createCustomer('a@b.com', 'Acme Co');

        $this->assertTrue($result['success']);
        $this->assertSame('cus_123', $result['id']);
        $this->assertSame('cus_123', $result['raw']['id']);
        $this->assertSame('a@b.com', $result['raw']['email']);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'api.stripe.com/v1/customers')
                && $request->hasHeader('Authorization', 'Bearer sk_test_dummy')
                && $request['email'] === 'a@b.com'
                && $request['name'] === 'Acme Co';
        });
    }

    public function test_charge_success_maps_payment_intent(): void
    {
        config()->set('services.stripe.secret', 'sk_test_dummy');

        $body = [
            'id' => 'pi_456',
            'object' => 'payment_intent',
            'status' => 'succeeded',
            'amount' => 1500,
            'currency' => 'usd',
        ];

        Http::fake([
            'api.stripe.com/*' => Http::response($body, 200),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->charge('cus_123', 1500, 'usd', 'Test charge');

        $this->assertTrue($result['success']);
        $this->assertSame('pi_456', $result['id']);
        $this->assertSame('succeeded', $result['status']);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'api.stripe.com/v1/payment_intents')
                && $request->hasHeader('Authorization', 'Bearer sk_test_dummy')
                && $request['customer'] === 'cus_123'
                && (string) $request['amount'] === '1500'
                && $request['currency'] === 'usd'
                && $request['confirm'] === 'true'
                && $request['description'] === 'Test charge';
        });
    }

    public function test_charge_non_succeeded_status_maps_success_false(): void
    {
        config()->set('services.stripe.secret', 'sk_test_dummy');

        $body = [
            'id' => 'pi_789',
            'object' => 'payment_intent',
            'status' => 'requires_action',
        ];

        Http::fake([
            'api.stripe.com/*' => Http::response($body, 200),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->charge('cus_123', 2000);

        $this->assertFalse($result['success']);
        $this->assertSame('pi_789', $result['id']);
        $this->assertSame('requires_action', $result['status']);
    }

    public function test_create_customer_not_configured_returns_503_and_sends_nothing(): void
    {
        config()->set('services.stripe.secret', '');

        Http::fake([
            'api.stripe.com/*' => Http::response(['id' => 'cus_should_not_happen'], 200),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->createCustomer('a@b.com', 'Acme Co');

        $this->assertTrue($result['error']);
        $this->assertSame(503, $result['status']);

        Http::assertNothingSent();
    }

    public function test_create_customer_error_pass_through_propagates_status(): void
    {
        config()->set('services.stripe.secret', 'sk_test_dummy');

        Http::fake([
            'api.stripe.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid API Key provided',
                    'type' => 'invalid_request_error',
                ],
            ], 500),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->createCustomer('a@b.com');

        $this->assertTrue($result['error']);
        $this->assertSame(500, $result['status']);
        $this->assertArrayNotHasKey('success', $result);
    }

    public function test_charge_error_pass_through_propagates_status(): void
    {
        config()->set('services.stripe.secret', 'sk_test_dummy');

        Http::fake([
            'api.stripe.com/*' => Http::response([
                'error' => [
                    'message' => 'Your card was declined.',
                    'type' => 'card_error',
                ],
            ], 422),
        ]);

        $service = app(StripePaymentService::class);

        $result = $service->charge('cus_123', 999);

        $this->assertTrue($result['error']);
        $this->assertSame(422, $result['status']);
        $this->assertArrayNotHasKey('success', $result);
    }
}
