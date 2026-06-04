<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold Motor Vehicle Records (MVR) aggregator client — provider-agnostic
 * (e.g. SambaSafety). PAID service; there is no single government MVR API, so
 * this targets a representative order/poll REST shape exposed by the aggregator.
 *
 * Credentials (config/services.php → 'mvr'):
 *   - services.mvr.base_url  (MVR_BASE_URL)   aggregator API root
 *   - services.mvr.api_key   (MVR_API_KEY)    bearer token
 *   - services.mvr.provider  (MVR_PROVIDER)   label, e.g. "sambasafety"
 *
 * Compliance: MVR pulls are regulated by the DPPA and, when used for hiring,
 * the FCRA. A record may only be ordered with the driver's signed consent;
 * callers MUST capture that consent before invoking orderRecord(). This client
 * never crashes when unconfigured — it degrades to a clean error array.
 */
class MvrApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'MVR Aggregator API';

    protected string $apiKey;

    protected string $provider;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.mvr.base_url');
        $this->apiKey = (string) config('services.mvr.api_key');
        $this->provider = (string) config('services.mvr.provider', 'sambasafety');
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
     * Place an MVR order. Payload carries the driver identifiers, license, and
     * the captured consent flag. Returns the aggregator's order envelope.
     */
    public function orderRecord(array $payload): array
    {
        return $this->post('orders', $payload);
    }

    /**
     * Poll a previously placed order for its status / completed record.
     */
    public function getRecord(string $orderId): array
    {
        return $this->get('orders/' . rawurlencode($orderId));
    }
}
