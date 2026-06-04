<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * NHTSA vPIC (Vehicle Product Information Catalog) — VIN decoding.
 *
 * Public, keyless US DOT / NHTSA web service. No credentials are required, so
 * isConfigured() stays true (inherited) and every call degrades to the base
 * class error array on transport / HTTP failures rather than throwing.
 *
 * Docs: https://vpic.nhtsa.dot.gov/api/
 */
class NhtsaVpicAPI extends AbstractIntegrationApi
{
    protected string $apiTitle = 'NHTSA vPIC API';
    protected string $baseUrl = 'https://vpic.nhtsa.dot.gov/api/';

    /**
     * Toggles application/x-www-form-urlencoded encoding for a single request.
     * Set transiently by postForm() and read by client().
     */
    protected bool $asForm = false;

    /**
     * Decode a single VIN into a flat field map.
     *
     * The useful payload is json['Results'][0]; callers should read that node.
     */
    public function decodeVin(string $vin, ?int $year = null): array
    {
        $query = ['format' => 'json'];

        if ($year !== null) {
            $query['modelyear'] = $year;
        }

        return $this->get('vehicles/DecodeVinValues/' . rawurlencode($vin), $query);
    }

    /**
     * Decode many VINs in a single round-trip. vPIC expects a semicolon-joined
     * list posted as application/x-www-form-urlencoded under the DATA key.
     *
     * The payload is json['Results'] (one flat field map per VIN).
     */
    public function decodeVinBatch(array $vins): array
    {
        return $this->postForm('vehicles/DecodeVinValuesBatch/', [
            'format' => 'json',
            'DATA' => implode(';', $vins),
        ]);
    }

    /**
     * Form-encoded POST. vPIC's batch endpoint only accepts form bodies, so we
     * mark the request ->asForm() and route through the base send() pipeline so
     * the success / error normalisation and not-configured guard still apply.
     */
    protected function postForm(string $endpoint, array $body = [], array $headers = []): array
    {
        $this->asForm = true;

        try {
            return $this->post($endpoint, $body, $headers);
        } finally {
            $this->asForm = false;
        }
    }

    protected function client(): PendingRequest
    {
        $client = parent::client();

        return $this->asForm ? $client->asForm() : $client;
    }
}
