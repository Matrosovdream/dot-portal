<?php

namespace App\Services\Document;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\DocusignApi;

/**
 * @scaffold App-facing service for e-signature of driver onboarding / lease docs,
 * fronting DocuSign via App\Mixins\Integrations\DocusignApi.
 *
 * Credentials live under config('services.docusign') — base_uri, account_id and a
 * ready-to-use OAuth access_token (token acquisition/refresh is out of scope; the
 * client reads it from config). When unconfigured every call degrades to a clean
 * error array rather than throwing.
 *
 * The returned envelope_id / status / signing url map onto the document records
 * we persist: a signed PDF lands in App\Models\File (files.filename / path / type)
 * and is linked to a driver via App\Models\DriverDocument (driver_documents.file_id
 * with type='esignature'). The DocuSign envelope_id is the external reference the
 * caller stores alongside that DriverDocument row to later poll status().
 */
class ESignatureService
{
    public function __construct(private readonly DocusignApi $api)
    {
    }

    /**
     * Build a single-document, single-signer envelope and send it.
     *
     * @param  string  $documentBase64  base64-encoded document bytes (e.g. a PDF)
     * @return array{success:bool, envelope_id:?string, status:?string}|array{error:bool, status:int, message:string}
     */
    public function sendForSignature(
        string $signerEmail,
        string $signerName,
        string $subject,
        string $documentBase64,
        string $documentName = 'document.pdf'
    ): array {
        $payload = [
            'emailSubject' => $subject,
            'status' => 'sent',
            'documents' => [[
                'documentBase64' => $documentBase64,
                'name' => $documentName,
                'fileExtension' => $this->fileExtension($documentName),
                'documentId' => '1',
            ]],
            'recipients' => [
                'signers' => [[
                    'email' => $signerEmail,
                    'name' => $signerName,
                    'recipientId' => '1',
                    'routingOrder' => '1',
                ]],
            ],
        ];

        $res = $this->api->createEnvelope($payload);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'envelope_id' => $res['envelopeId'] ?? null,
            'status' => $res['status'] ?? 'sent',
        ];
    }

    /**
     * Fetch an envelope's current signing state, normalised to a clean array.
     *
     * @return array{envelope_id:?string, status:?string, sent_at:?string, completed_at:?string, raw:array}|array{error:bool, status:int, message:string}
     */
    public function status(string $envelopeId): array
    {
        $res = $this->api->getEnvelope($envelopeId);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'envelope_id' => $res['envelopeId'] ?? $envelopeId,
            'status' => $res['status'] ?? null,
            'sent_at' => $res['sentDateTime'] ?? null,
            'completed_at' => $res['completedDateTime'] ?? null,
            'raw' => $res,
        ];
    }

    /**
     * Generate an embedded signing URL for the given signer on an envelope.
     *
     * @return array{success:bool, url:?string}|array{error:bool, status:int, message:string}
     */
    public function signingUrl(
        string $envelopeId,
        string $signerEmail,
        string $signerName,
        string $returnUrl
    ): array {
        $payload = [
            'returnUrl' => $returnUrl,
            'authenticationMethod' => 'none',
            'email' => $signerEmail,
            'userName' => $signerName,
            'recipientId' => '1',
        ];

        $res = $this->api->createRecipientView($envelopeId, $payload);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return [
            'success' => true,
            'url' => $res['url'] ?? null,
        ];
    }

    /**
     * Derive DocuSign's fileExtension hint from the document name.
     */
    private function fileExtension(string $documentName): string
    {
        $ext = pathinfo($documentName, PATHINFO_EXTENSION);

        return $ext !== '' ? strtolower($ext) : 'pdf';
    }
}
