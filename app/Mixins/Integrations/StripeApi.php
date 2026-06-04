<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * @scaffold Stripe payment gateway (https://api.stripe.com/v1/).
 * Provider: Stripe. Credentials: config('services.stripe.secret') — the
 * restricted/secret API key (sk_...). Stripe is PCI-DSS scoped: raw card PANs
 * must never touch this app; callers pass tokenized payment methods / customer
 * ids produced client-side (Stripe.js / Elements). This client degrades to a
 * clean 503 error array when the secret is missing — it never crashes.
 *
 * Stripe's REST API is form-encoded (application/x-www-form-urlencoded) and
 * returns JSON, so the client is built with ->asForm()->acceptJson().
 */
class StripeApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Stripe API';
    protected string $baseUrl = 'https://api.stripe.com/v1/';

    protected string $secret;

    public function __construct()
    {
        $this->secret = (string) config('services.stripe.secret', '');
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withToken($this->secret)
            ->asForm()
            ->acceptJson();
    }

    protected function isConfigured(): bool
    {
        return $this->secret !== '';
    }

    public function createCustomer(array $params): array
    {
        return $this->post('customers', $params);
    }

    public function createPaymentIntent(array $params): array
    {
        return $this->post('payment_intents', $params);
    }

    public function createSubscription(array $params): array
    {
        return $this->post('subscriptions', $params);
    }

    public function getCustomer(string $id): array
    {
        return $this->get("customers/{$id}");
    }
}
