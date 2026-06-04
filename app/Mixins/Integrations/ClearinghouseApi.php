<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold FMCSA Drug & Alcohol Clearinghouse client. The FMCSA exposes NO open
 * REST API for the Clearinghouse — programmatic access is brokered through a
 * Consortium/Third-Party Administrator (C/TPA) partner portal/endpoint. This
 * client targets that representative partner REST shape (submit query → poll).
 *
 * Credentials (config/services.php → 'clearinghouse'):
 *   - services.clearinghouse.base_url  (CLEARINGHOUSE_BASE_URL)  C/TPA API root
 *   - services.clearinghouse.api_key   (CLEARINGHOUSE_API_KEY)   bearer token
 *
 * Compliance: a Clearinghouse query may only be run with the driver's electronic
 * consent recorded in the Clearinghouse. A "limited" query needs general consent;
 * a "full" query needs specific electronic consent. Callers MUST capture that
 * consent before invoking submitQuery(); the consent flag is passed through in
 * the payload. This client never crashes when unconfigured — it degrades to a
 * clean error array.
 */
class ClearinghouseApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'FMCSA Clearinghouse API';

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.clearinghouse.base_url');
        $this->apiKey = (string) config('services.clearinghouse.api_key');
    }

    protected function client(): PendingRequest
    {
        return parent::client()->withToken($this->apiKey);
    }

    protected function isConfigured(): bool
    {
        return $this->baseUrl !== '' && $this->apiKey !== '';
    }

    /**
     * Submit a Clearinghouse query for a driver. Payload carries the driver
     * identifiers, license, the query type ('limited'|'full') and the captured
     * consent flag. Returns the partner's query envelope.
     */
    public function submitQuery(array $payload): array
    {
        return $this->post('queries', $payload);
    }

    /**
     * Poll a previously submitted query for its status / completed result.
     */
    public function getQuery(string $queryId): array
    {
        return $this->get('queries/' . rawurlencode($queryId));
    }
}
