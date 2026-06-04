<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold DocuSign eSignature REST client (eSignature REST API v2.1) — used to
 * send driver onboarding / lease documents out for signature. PAID service; this
 * targets DocuSign's documented envelopes/views endpoint shape under an account.
 *
 * Credentials (config/services.php → 'docusign'):
 *   - services.docusign.base_uri      (DOCUSIGN_BASE_URI)    e.g. https://demo.docusign.net
 *   - services.docusign.account_id    (DOCUSIGN_ACCOUNT_ID)  API account GUID
 *   - services.docusign.access_token  (DOCUSIGN_ACCESS_TOKEN) OAuth bearer token
 *
 * Auth: DocuSign uses OAuth 2.0; acquiring/refreshing the access token (JWT or
 * Authorization Code grant) is OUT OF SCOPE here — it is read ready-to-use from
 * config and attached via ->withToken(). The base URL is assembled per-account:
 * "{base_uri}/restapi/v2.1/accounts/{account_id}/".
 *
 * Compliance: e-signatures captured here support consent / authorization records
 * (onboarding, lease agreements). This client never crashes when unconfigured —
 * it degrades to a clean error array via the base class.
 */
class DocusignApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'DocuSign eSignature API';

    protected string $accessToken;

    public function __construct()
    {
        $baseUri = rtrim((string) config('services.docusign.base_uri'), '/');
        $accountId = (string) config('services.docusign.account_id');

        $this->accessToken = (string) config('services.docusign.access_token');
        $this->baseUrl = $baseUri . '/restapi/v2.1/accounts/' . $accountId . '/';
    }

    protected function client(): PendingRequest
    {
        return parent::client()->withToken($this->accessToken);
    }

    protected function isConfigured(): bool
    {
        return (string) config('services.docusign.account_id') !== '' && $this->accessToken !== '';
    }

    /**
     * Create (and typically send) an envelope. Payload is the DocuSign envelope
     * definition (documents, recipients, status, etc.).
     */
    public function createEnvelope(array $payload): array
    {
        return $this->post('envelopes', $payload);
    }

    /**
     * Fetch an envelope's current state (status, timestamps, recipients).
     */
    public function getEnvelope(string $envelopeId): array
    {
        return $this->get('envelopes/' . rawurlencode($envelopeId));
    }

    /**
     * Generate an embedded signing (recipient) view URL for an envelope.
     */
    public function createRecipientView(string $envelopeId, array $payload): array
    {
        return $this->post('envelopes/' . rawurlencode($envelopeId) . '/views/recipient', $payload);
    }
}
