<?php

namespace App\Mixins\Integrations;

use Illuminate\Http\Client\PendingRequest;

/**
 * @scaffold Samsara — ELD / telematics provider (HOS logs, vehicle
 * locations, DVIRs). Real, public REST API at https://api.samsara.com; it only
 * needs an API token, so this client talks to the documented endpoint shapes
 * directly rather than mocking them.
 *
 * Credentials (config/services.php → 'samsara'):
 *   - services.samsara.api_token  (SAMSARA_API_TOKEN)  bearer token
 *   - services.samsara.base_url   (SAMSARA_BASE_URL)   defaults to https://api.samsara.com
 *
 * Compliance: HOS / ELD records are FMCSA hours-of-service data tied to named
 * drivers; only fetch logs for vehicles in the carrier's own fleet under the
 * account the token belongs to. When the token is missing every call degrades
 * to a normalised 503 error array instead of hitting the upstream service.
 */
class SamsaraApi extends AbstractIntegrationApi
{
    protected string $apiTitle = 'Samsara API';

    protected string $apiToken = '';

    public function __construct()
    {
        $this->apiToken = (string) (config('services.samsara.api_token') ?? '');

        $base = (string) (config('services.samsara.base_url') ?? 'https://api.samsara.com');
        $this->baseUrl = rtrim($base, '/') . '/';
    }

    /**
     * Samsara authenticates with a bearer API token on every request.
     */
    protected function client(): PendingRequest
    {
        return parent::client()->withToken($this->apiToken);
    }

    protected function isConfigured(): bool
    {
        return $this->apiToken !== '';
    }

    /**
     * List fleet vehicles. GET fleet/vehicles
     */
    public function vehicles(array $query = []): array
    {
        return $this->get('fleet/vehicles', $query);
    }

    /**
     * Current vehicle GPS locations. GET fleet/vehicles/locations
     */
    public function vehicleLocations(array $query = []): array
    {
        return $this->get('fleet/vehicles/locations', $query);
    }

    /**
     * Hours-of-service (ELD) logs for a time window. GET fleet/hos/logs
     */
    public function hosLogs(string $startTime, string $endTime, array $query = []): array
    {
        return $this->get('fleet/hos/logs', array_merge([
            'startTime' => $startTime,
            'endTime' => $endTime,
        ], $query));
    }

    /**
     * Driver/vehicle inspection report (DVIR) history for a time window.
     * GET fleet/dvirs/history
     */
    public function dvirs(string $startTime, string $endTime, array $query = []): array
    {
        return $this->get('fleet/dvirs/history', array_merge([
            'startTime' => $startTime,
            'endTime' => $endTime,
        ], $query));
    }
}
