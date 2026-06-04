<?php

namespace App\Services\Payments;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\PlaidApi;

/**
 * App-facing entry point for Plaid bank verification / ACH onboarding.
 *
 * Validates and shapes input, calls the Plaid client, and maps raw Plaid
 * responses into clean snake_case domain arrays aligned to our stored bank
 * account columns. Error arrays from the client are passed straight through.
 */
class BankVerificationService
{
    public function __construct(private readonly PlaidApi $api)
    {
    }

    /**
     * Create a Plaid Link token for the given application user.
     */
    public function createLinkToken(string $userId, string $clientName = 'DotPortal'): array
    {
        return $this->api->createLinkToken([
            'user' => ['client_user_id' => $userId],
            'client_name' => $clientName,
            'products' => ['auth'],
            'country_codes' => ['US'],
            'language' => 'en',
        ]);
    }

    /**
     * Exchange a public_token for a permanent access_token + item_id.
     */
    public function exchange(string $publicToken): array
    {
        $res = $this->api->exchangePublicToken($publicToken);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'access_token' => $res['access_token'] ?? null,
            'item_id' => $res['item_id'] ?? null,
        ];
    }

    /**
     * Resolve normalised account + ACH routing data for a stored access_token.
     */
    public function account(string $accessToken): array
    {
        $res = $this->api->getAuth($accessToken);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        $account = $res['accounts'][0] ?? [];
        $numbers = $res['numbers']['ach'][0] ?? [];

        return $this->mapAccount($account, $numbers);
    }

    /**
     * Shape one Plaid account + ACH numbers entry into our bank account columns.
     */
    private function mapAccount(array $account, array $numbers): array
    {
        $balances = $account['balances'] ?? [];

        return [
            'success' => true,
            'account_id' => $account['account_id'] ?? ($numbers['account_id'] ?? null),
            'account_name' => $account['name'] ?? null,
            'official_name' => $account['official_name'] ?? null,
            'mask' => $account['mask'] ?? null,
            'type' => $account['type'] ?? null,
            'subtype' => $account['subtype'] ?? null,
            'routing_number' => $numbers['routing'] ?? null,
            'account_number' => $numbers['account'] ?? null,
            'wire_routing_number' => $numbers['wire_routing'] ?? null,
            'available_balance' => $balances['available'] ?? null,
            'current_balance' => $balances['current'] ?? null,
            'currency' => $balances['iso_currency_code'] ?? null,
        ];
    }
}
