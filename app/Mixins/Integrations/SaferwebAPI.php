<?php

namespace App\Mixins\Integrations;

use Illuminate\Support\Facades\Log;

class SaferwebAPI
{
    protected string $baseUrl = 'https://saferwebapi.com/v3/';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.saferweb.api_key');
    }

    // 1. Inspection History
    public function getInspectionHistory(string $usdot): array|string|null
    {
        return $this->request("history/inspection/{$usdot}");
    }

    // 2. Crashes
    public function getCrashHistory(string $usdot): array|string|null
    {
        return $this->request("history/crash/{$usdot}");
    }

    // 3. Safety Ratings
    public function getSafetyRatings(string $usdot): array|string|null
    {
        return $this->request("snapshots/safety/{$usdot}");
    }

    // 4. Out of Service (OOS) Rates
    public function getOOSRates(string $usdot): array|string|null
    {
        return $this->request("snapshots/oos/{$usdot}");
    }

    // 5. Inspections Summary
    public function getInspectionSummary(string $usdot): array|string|null
    {
        return $this->request("snapshots/inspection/{$usdot}");
    }

    // 6. Insurance History
    public function getInsuranceHistory(string $usdot): array|string|null
    {
        return $this->request("history/insurance/{$usdot}");
    }

    // 7. Company Details
    public function getCompanySnapshot(string $usdot): array|string|null
    {
        return $this->request("snapshots/company/{$usdot}");
    }

    // 8. Power Units and Drivers
    public function getEquipmentSnapshot(string $usdot): array|string|null
    {
        return $this->request("snapshots/equipment/{$usdot}");
    }

    protected function request(string $endpoint): array|string|null
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'x-api-key: ' . $this->apiKey,
            ],
        ]);

        try {
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error = curl_error($curl);
                Log::error('SaferWeb API error: ' . $error);
                return null;
            }

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('SaferWeb API Exception: ' . $e->getMessage());
            return null;
        } finally {
            curl_close($curl);
        }
    }

}
