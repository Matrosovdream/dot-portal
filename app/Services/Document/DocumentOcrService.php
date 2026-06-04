<?php

namespace App\Services\Document;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\DocumentOcrApi;

/**
 * @scaffold Document OCR service — app-facing entry point for field extraction.
 *
 * Provider: provider-agnostic OCR backend (AWS Textract / Google Document AI /
 *           custom) behind DocumentOcrApi.
 * Endpoint: POST {services.ocr.base_url}analyze
 * Key:      config('services.ocr.api_key') (Bearer); provider hint
 *           config('services.ocr.provider').
 *
 * Maps raw OCR output into clean snake_case domain arrays aligned to the
 * DriverCdlLicense / DriverMedicalCard models. Error arrays from the client are
 * passed straight through unchanged. Extracted values are driver PII (DPPA /
 * FCRA) and must only be processed with the driver's consent.
 */
class DocumentOcrService
{
    public function __construct(private readonly DocumentOcrApi $api) {}

    /**
     * Extract CDL fields from a document image/URL and shape them for
     * App\Models\DriverCdlLicense.
     */
    public function extractCdl(string $documentUrl): array
    {
        $res = $this->api->analyze([
            'document_url' => $documentUrl,
            'document_type' => 'cdl',
        ]);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return $this->mapCdl($res);
    }

    /**
     * Extract medical-examiner certificate fields from a document image/URL and
     * shape them for App\Models\DriverMedicalCard.
     */
    public function extractMedicalCard(string $documentUrl): array
    {
        $res = $this->api->analyze([
            'document_url' => $documentUrl,
            'document_type' => 'medical_card',
        ]);

        if (AbstractIntegrationApi::failed($res)) {
            return $res;
        }

        return $this->mapMedicalCard($res);
    }

    /**
     * @param  array  $res  raw OCR response
     */
    private function mapCdl(array $res): array
    {
        $fields = $this->fields($res);

        return [
            'license_number' => $this->value($fields, ['license_number', 'document_number', 'dl_number']),
            'license_state' => $this->value($fields, ['license_state', 'state', 'issuing_state']),
            'expiration_date' => $this->value($fields, ['expiration_date', 'expires', 'exp_date']),
            'class' => $this->value($fields, ['class', 'license_class', 'vehicle_class']),
            'raw' => $res,
        ];
    }

    /**
     * @param  array  $res  raw OCR response
     */
    private function mapMedicalCard(array $res): array
    {
        $fields = $this->fields($res);

        return [
            'examiner_name' => $this->value($fields, ['examiner_name', 'medical_examiner', 'name']),
            'national_registry' => $this->value($fields, ['national_registry', 'national_registry_number', 'registry_number']),
            'issue_date' => $this->value($fields, ['issue_date', 'issued', 'examination_date']),
            'expiration_date' => $this->value($fields, ['expiration_date', 'expires', 'exp_date']),
            'raw' => $res,
        ];
    }

    /**
     * OCR backends commonly nest the parsed key/value pairs under a "fields" (or
     * "data") envelope; fall back to the top-level body otherwise.
     */
    private function fields(array $res): array
    {
        foreach (['fields', 'data', 'result'] as $key) {
            if (isset($res[$key]) && is_array($res[$key])) {
                return $res[$key];
            }
        }

        return $res;
    }

    /**
     * First non-empty value among the candidate keys, normalised to a string.
     *
     * @param  array  $fields  parsed key/value pairs
     * @param  array  $keys    candidate field names, in priority order
     */
    private function value(array $fields, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $fields[$key] ?? null;

            if (is_array($value)) {
                $value = $value['value'] ?? null;
            }

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }

            if (is_scalar($value)) {
                return (string) $value;
            }
        }

        return null;
    }
}
