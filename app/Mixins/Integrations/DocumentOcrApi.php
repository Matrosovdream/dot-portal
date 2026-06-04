<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold Document OCR client (provider-agnostic).
 *
 * Provider: configurable OCR backend (AWS Textract / Google Document AI /
 *           custom service) fronted by a single HTTP endpoint.
 * Endpoint: POST {services.ocr.base_url}analyze
 * Key:      Bearer token from config('services.ocr.api_key');
 *           provider hint from config('services.ocr.provider').
 *
 * Compliance: extracted CDL / medical-card data is driver PII governed by the
 * DPPA and FCRA. Documents must only be submitted with the driver's consent and
 * never logged in clear. When base_url / api_key are absent the client degrades
 * to a clean error array (503) and performs no network call.
 */
class DocumentOcrApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Document OCR API';

    protected string $apiKey;

    protected string $provider;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.ocr.base_url');
        $this->apiKey = (string) config('services.ocr.api_key');
        $this->provider = (string) config('services.ocr.provider');
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
     * Submit a document for field extraction.
     *
     * @param  array  $payload  document_url OR document_base64, plus document_type
     * @return array decoded response, or normalised error array
     */
    public function analyze(array $payload): array
    {
        if ($this->provider !== '' && ! isset($payload['provider'])) {
            $payload['provider'] = $this->provider;
        }

        return $this->post('analyze', $payload);
    }
}
