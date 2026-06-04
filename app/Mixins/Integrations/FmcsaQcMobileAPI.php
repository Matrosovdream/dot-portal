<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * FMCSA QCMobile / SAFER web services — official carrier data by USDOT.
 *
 * Public, government-operated read API. Authentication is a free "webKey"
 * (request one at https://mobile.fmcsa.dot.gov/QCDevsite/) passed as the
 * 'webKey' query parameter on every request. Base URL:
 *
 *     https://mobile.fmcsa.dot.gov/qc/services/
 *
 * Configure with services.fmcsa.web_key (env FMCSA_WEB_KEY). When the key is
 * missing every call degrades to a normalised 503 error array instead of
 * hitting the upstream service.
 */
class FmcsaQcMobileAPI extends AbstractIntegrationApi
{
    protected string $apiTitle = 'FMCSA QCMobile API';
    protected string $baseUrl = 'https://mobile.fmcsa.dot.gov/qc/services/';

    protected string $webKey = '';

    public function __construct()
    {
        $this->webKey = (string) (config('services.fmcsa.web_key') ?? '');
    }

    /**
     * Attach the FMCSA webKey to every request's query string. FMCSA expects
     * it as the 'webKey' query parameter on all GET endpoints.
     */
    protected function client(): PendingRequest
    {
        return parent::client()->withQueryParameters([
            'webKey' => $this->webKey,
        ]);
    }

    protected function isConfigured(): bool
    {
        return $this->webKey !== '';
    }

    /**
     * Carrier record by USDOT number. GET carriers/{dot}
     */
    public function carrierByDot(string $dot): array
    {
        return $this->get('carriers/' . rawurlencode($dot));
    }

    /**
     * BASIC (Behavior Analysis and Safety Improvement Category) measures by
     * USDOT number. GET carriers/{dot}/basics
     */
    public function basicsByDot(string $dot): array
    {
        return $this->get('carriers/' . rawurlencode($dot) . '/basics');
    }

    /**
     * Carrier record by MC/MX/FF docket number. GET carriers/docket-number/{docket}
     */
    public function carrierByDocket(string $docket): array
    {
        return $this->get('carriers/docket-number/' . rawurlencode($docket));
    }

    /**
     * Carrier search by (partial) legal/DBA name. GET carriers/name/{name}
     */
    public function searchByName(string $name): array
    {
        return $this->get('carriers/name/' . rawurlencode($name));
    }
}
