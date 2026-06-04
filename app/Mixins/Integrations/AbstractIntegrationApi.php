<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Base class for outbound third-party API clients (the transport layer).
 *
 * Subclasses set $baseUrl / $apiTitle, override client() to attach auth and
 * isConfigured() to declare required credentials, then expose domain methods
 * that call get()/post()/put()/delete(). Every call returns either the decoded
 * JSON body (array) on success, or a normalised error array:
 *
 *     ['error' => true, 'status' => int, 'message' => string]
 *
 * This mirrors the shape already used by SaferwebAPI / TranspGovAPI so callers
 * and Services can treat all integrations uniformly. Use static failed() to
 * detect an error result.
 */
abstract class AbstractIntegrationApi
{
    protected string $apiTitle = 'Integration API';
    protected string $baseUrl = '';
    protected int $timeout = 30;

    /**
     * Build the authenticated HTTP client. Override per integration to attach
     * bearer tokens, basic auth, api-key headers, etc.
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson();
    }

    /**
     * Whether the required credentials are present. Override in subclasses so
     * a missing key returns a clean 503 error instead of throwing.
     */
    protected function isConfigured(): bool
    {
        return true;
    }

    protected function get(string $endpoint, array $query = [], array $headers = []): array
    {
        return $this->send('get', $endpoint, $query, $headers);
    }

    protected function post(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->send('post', $endpoint, $body, $headers);
    }

    protected function put(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->send('put', $endpoint, $body, $headers);
    }

    protected function delete(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->send('delete', $endpoint, $body, $headers);
    }

    /**
     * Perform a request and normalise the success / error response shape.
     */
    protected function send(string $method, string $endpoint, array $payload = [], array $headers = []): array
    {
        if (! $this->isConfigured()) {
            return $this->notConfigured();
        }

        try {
            $request = $this->client();

            if ($headers) {
                $request = $request->withHeaders($headers);
            }

            /** @var Response $response */
            $response = $request->{$method}($endpoint, $payload);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            return $this->error(
                $response->status(),
                $response->json('message') ?? $response->json('error') ?? $response->body(),
                $endpoint
            );
        } catch (\Throwable $e) {
            return $this->error(500, $e->getMessage(), $endpoint);
        }
    }

    protected function error(int $status, $message, string $endpoint = ''): array
    {
        $message = is_string($message) ? $message : json_encode($message);

        Log::error($this->apiTitle . ' error', [
            'endpoint' => $endpoint,
            'status' => $status,
            'message' => $message,
        ]);

        return [
            'error' => true,
            'status' => $status,
            'message' => $message,
        ];
    }

    protected function notConfigured(): array
    {
        Log::warning($this->apiTitle . ' is not configured.');

        return [
            'error' => true,
            'status' => 503,
            'message' => $this->apiTitle . ' is not configured. Add the required credentials to config/services.php.',
        ];
    }

    /**
     * Detect a normalised error result returned by any integration call.
     */
    public static function failed(?array $result): bool
    {
        return ! is_array($result) || ($result['error'] ?? false) === true;
    }
}
