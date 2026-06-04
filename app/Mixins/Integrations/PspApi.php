<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold FMCSA Pre-Employment Screening Program (PSP) client — driver hiring
 * history (5yr crash / 3yr roadside inspection records). PAID per report; PSP is
 * distributed via NIC Technologies under contract, so this targets a
 * representative order/poll REST shape rather than a free public endpoint.
 *
 * Credentials (config/services.php → 'psp'):
 *   - services.psp.base_url    (PSP_BASE_URL)    PSP API root
 *   - services.psp.account_id  (PSP_ACCOUNT_ID)  motor-carrier account identifier
 *   - services.psp.api_key     (PSP_API_KEY)     bearer token
 *
 * Compliance: a PSP report is a consumer report under the FCRA. It may only be
 * ordered for employment purposes with the applicant's signed consent; callers
 * MUST capture and retain that consent before invoking orderReport(). This
 * client never crashes when unconfigured — it degrades to a clean error array.
 */
class PspApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'FMCSA PSP API';

    protected string $accountId;

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.psp.base_url');
        $this->accountId = (string) config('services.psp.account_id');
        $this->apiKey = (string) config('services.psp.api_key');
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
     * Order a PSP report. Payload carries the applicant identifiers, license, and
     * the captured consent flag; the carrier account_id is attached server-side.
     * Returns the PSP order envelope.
     */
    public function orderReport(array $payload): array
    {
        $payload['account_id'] = $this->accountId;

        return $this->post('reports', $payload);
    }

    /**
     * Poll a previously ordered report for its status / completed history.
     */
    public function getReport(string $reportId): array
    {
        return $this->get('reports/' . rawurlencode($reportId), [
            'account_id' => $this->accountId,
        ]);
    }
}
