<?php

namespace App\Mixins\Integrations;

/**
 * @scaffold Smarty (SmartyStreets) US Street Address API.
 *
 * Provider: Smarty (https://www.smarty.com). Credentials: a website/secret key
 * pair exposed here as auth-id / auth-token (config('services.smarty.auth_id'),
 * config('services.smarty.auth_token')). Smarty's US Street Address service is a
 * paid product with no free public REST endpoint, so this client is wired against
 * the documented endpoint shape and stays dormant until credentials are present.
 *
 * Compliance: address verification only confirms deliverability against USPS
 * data; it is NOT a source of personal/consumer information, so it sits outside
 * DPPA/FCRA. Do not repurpose the returned data for identity or eligibility
 * decisions — those flows must go through the consent-gated MVR/background paths.
 *
 * When unconfigured every call degrades to a clean error array (status 503) via
 * the base class — it never throws.
 */
class SmartyApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Smarty US Street Address API';
    protected string $baseUrl = 'https://us-street.api.smarty.com/';

    protected string $authId;
    protected string $authToken;

    public function __construct()
    {
        $this->authId = (string) config('services.smarty.auth_id', '');
        $this->authToken = (string) config('services.smarty.auth_token', '');
    }

    protected function isConfigured(): bool
    {
        return $this->authId !== '' && $this->authToken !== '';
    }

    /**
     * Verify and standardize a single US street address.
     *
     * Smarty returns a JSON array of candidate matches (empty array = no match).
     * Accepts ['street','city','state','zipcode'] and merges in candidates=1 plus
     * the auth credentials as query params.
     */
    public function verify(array $address): array
    {
        $query = array_merge(
            [
                'street' => $address['street'] ?? null,
                'city' => $address['city'] ?? null,
                'state' => $address['state'] ?? null,
                'zipcode' => $address['zipcode'] ?? null,
                'candidates' => 1,
            ],
            $this->authParams()
        );

        return $this->get('street-address', array_filter(
            $query,
            static fn ($value) => $value !== null && $value !== ''
        ));
    }

    /**
     * Smarty authenticates the embedded/REST API via query-string credentials.
     */
    protected function authParams(): array
    {
        return [
            'auth-id' => $this->authId,
            'auth-token' => $this->authToken,
        ];
    }
}
