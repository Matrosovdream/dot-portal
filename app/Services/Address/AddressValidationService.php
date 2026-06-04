<?php

namespace App\Services\Address;

use App\Mixins\Integrations\AbstractIntegrationApi;
use App\Mixins\Integrations\SmartyApi;

/**
 * App-facing entry point for US street address validation (Smarty).
 *
 * Shapes input for the transport client, then maps the raw Smarty candidate list
 * into a clean domain array. The normalized block uses keys that align to the
 * App\Models\UserCompanyAddress columns so a verified address can be persisted
 * with minimal translation:
 *
 *     normalized.line1   -> address1
 *     normalized.city    -> city
 *     normalized.state   -> state_id is a FK to ref_country_states; map the
 *                           two-letter abbreviation to its state row before save
 *     normalized.zipcode -> zip (combine with plus4 for ZIP+4 when storing)
 *     normalized.plus4   -> (4-digit add-on; not a standalone column)
 *
 * Note: UserCompanyAddress has no address2/secondary column of its own here, and
 * 'state' is returned as the USPS abbreviation rather than the numeric state_id.
 */
class AddressValidationService
{
    /**
     * DPV match codes Smarty considers a confirmed delivery point.
     * Y = confirmed, S = confirmed (secondary dropped), D = confirmed (missing secondary).
     */
    private const VALID_DPV_CODES = ['Y', 'S', 'D'];

    public function __construct(private readonly SmartyApi $api)
    {
    }

    /**
     * Validate a US address.
     *
     * @param  array  $address  ['street','city','state','zipcode']
     * @return array  Error array is passed straight through; otherwise:
     *                ['valid'=>bool,'dpv_match_code'=>?string,
     *                 'normalized'=>['line1','city','state','zipcode','plus4'],
     *                 'raw'=>array]
     */
    public function validate(array $address): array
    {
        $result = $this->api->verify($address);

        if (AbstractIntegrationApi::failed($result)) {
            return $result;
        }

        $candidate = $this->firstCandidate($result);
        $dpvMatchCode = $this->dpvMatchCode($candidate);

        return [
            'valid' => in_array($dpvMatchCode, self::VALID_DPV_CODES, true),
            'dpv_match_code' => $dpvMatchCode,
            'normalized' => $this->mapNormalized($candidate),
            'raw' => $result,
        ];
    }

    /**
     * Smarty returns a flat JSON array of candidate objects; take the first.
     */
    private function firstCandidate(array $result): ?array
    {
        $candidate = $result[0] ?? null;

        return is_array($candidate) ? $candidate : null;
    }

    private function dpvMatchCode(?array $candidate): ?string
    {
        $code = $candidate['analysis']['dpv_match_code'] ?? null;

        return $code !== null ? (string) $code : null;
    }

    /**
     * Map a Smarty candidate into model-aligned address parts.
     */
    private function mapNormalized(?array $candidate): array
    {
        $components = $candidate['components'] ?? [];

        return [
            'line1' => $candidate['delivery_line_1'] ?? null,
            'city' => $components['city_name'] ?? null,
            'state' => $components['state_abbreviation'] ?? null,
            'zipcode' => $components['zipcode'] ?? null,
            'plus4' => $components['plus4_code'] ?? null,
        ];
    }
}
