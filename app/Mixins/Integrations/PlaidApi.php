<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * @scaffold Plaid (https://plaid.com) — bank account verification / ACH used for
 * subscription billing. Credentials (client_id + secret) are sent in the JSON
 * request BODY of every call, not in headers. Live calls require a paid Plaid
 * account; an account/routing number returned by auth/get is sensitive financial
 * data and must only be used with the carrier's explicit consent and stored per
 * the relevant ACH/NACHA and GLBA safeguarding requirements. Without credentials
 * this client degrades to a clean error array (never crashes).
 */
class PlaidApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Plaid API';
    protected int $timeout = 30;

    protected string $clientId;
    protected string $secret;
    protected string $env;

    public function __construct()
    {
        $this->clientId = (string) config('services.plaid.client_id');
        $this->secret = (string) config('services.plaid.secret');
        $this->env = (string) config('services.plaid.env', 'sandbox');

        $this->baseUrl = $this->resolveBaseUrl($this->env);
    }

    /**
     * Map the configured environment to the matching Plaid host. Plaid
     * decommissioned the Development environment in June 2024, so only Sandbox
     * and Production remain; any other value (including the legacy
     * 'development') falls back to Sandbox.
     */
    protected function resolveBaseUrl(string $env): string
    {
        $hosts = [
            'sandbox' => 'https://sandbox.plaid.com/',
            'production' => 'https://production.plaid.com/',
        ];

        return $hosts[$env] ?? $hosts['sandbox'];
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson()
            ->asJson();
    }

    protected function isConfigured(): bool
    {
        return $this->clientId !== '' && $this->secret !== '';
    }

    /**
     * Credentials merged into the body of every Plaid request.
     */
    protected function creds(): array
    {
        return [
            'client_id' => $this->clientId,
            'secret' => $this->secret,
        ];
    }

    /**
     * Create a short-lived link_token used to initialise Plaid Link on the client.
     */
    public function createLinkToken(array $params): array
    {
        return $this->post('link/token/create', array_merge($this->creds(), $params));
    }

    /**
     * Exchange a public_token (from Link onSuccess) for a permanent access_token.
     */
    public function exchangePublicToken(string $publicToken): array
    {
        return $this->post('item/public_token/exchange', array_merge($this->creds(), [
            'public_token' => $publicToken,
        ]));
    }

    /**
     * Fetch account and ACH auth (routing/account number) data for an Item.
     */
    public function getAuth(string $accessToken): array
    {
        return $this->post('auth/get', array_merge($this->creds(), [
            'access_token' => $accessToken,
        ]));
    }
}
