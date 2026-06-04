<?php

namespace App\Services\Payments;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\StripeApi;

/**
 * App-facing entry point for the Stripe gateway.
 *
 * This is a STANDALONE parallel payment gateway. It is intentionally NOT bound
 * over App\Contracts\Payment\PaymentInterface and does NOT replace
 * AuthnetService — the two gateways run side by side.
 *
 * Each method shapes input for Stripe, calls the client, then maps the raw
 * (form-encoded -> JSON) response into a clean snake_case domain array. Error
 * arrays from the transport layer are passed straight through.
 */
class StripePaymentService
{
    public function __construct(private readonly StripeApi $api) {}

    /**
     * Create a Stripe customer.
     *
     * @return array{success:bool,id:?string,raw:array}|array
     */
    public function createCustomer(string $email, ?string $name = null): array
    {
        $params = ['email' => $email];

        if ($name !== null && $name !== '') {
            $params['name'] = $name;
        }

        $res = $this->api->createCustomer($params);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'id' => $res['id'] ?? null,
            'raw' => $res,
        ];
    }

    /**
     * Charge a customer by creating a confirmed PaymentIntent.
     *
     * @return array{success:bool,id:?string,status:?string}|array
     */
    public function charge(
        string $customerId,
        int $amountCents,
        string $currency = 'usd',
        ?string $description = null
    ): array {
        $params = [
            'customer' => $customerId,
            'amount' => $amountCents,
            'currency' => $currency,
            'confirm' => 'true',
        ];

        if ($description !== null && $description !== '') {
            $params['description'] = $description;
        }

        $res = $this->api->createPaymentIntent($params);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return $this->mapPaymentIntent($res);
    }

    /**
     * Subscribe a customer to a recurring price.
     *
     * @return array{success:bool,id:?string,status:?string}|array
     */
    public function createSubscription(string $customerId, string $priceId): array
    {
        $params = [
            'customer' => $customerId,
            'items' => [
                ['price' => $priceId],
            ],
        ];

        $res = $this->api->createSubscription($params);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'id' => $res['id'] ?? null,
            'status' => $res['status'] ?? null,
        ];
    }

    /**
     * Map a Stripe PaymentIntent resource into a clean domain array.
     *
     * @param array<string,mixed> $intent
     * @return array{success:bool,id:?string,status:?string}
     */
    private function mapPaymentIntent(array $intent): array
    {
        return [
            'success' => ($intent['status'] ?? null) === 'succeeded',
            'id' => $intent['id'] ?? null,
            'status' => $intent['status'] ?? null,
        ];
    }
}
