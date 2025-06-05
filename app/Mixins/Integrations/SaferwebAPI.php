<?php

namespace App\Mixins\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaferwebAPI
{
    protected string $baseUrl = 'https://saferwebapi.com/';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.saferweb.api_key');
    }

    public function getInspectionHistory(string $usdot): array|null
    {
        return $this->request("v3/history/inspection/{$usdot}");
    }

    public function getCrashHistory(string $usdot): array|null
    {
        return $this->request("v3/history/crash/{$usdot}");
    }

    public function getInspectionSummary(string $usdot): array|null
    {
        return $this->request("v3/history/inspection/{$usdot}");
    }

    public function getCompanySnapshot(string $usdot): array|null
    {
        return $this->request("/v2/usdot/snapshot/{$usdot}");
    }

    public function getHistoryAll(string $usdot): array|null
    {
        return $this->request("v3/history/everything/{$usdot}");
    }

    protected function request(string $endpoint): array|null
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->timeout(30)->get($this->baseUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('SaferWeb API error', [
                    'url' => $endpoint,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('SaferWeb API Exception: ' . $e->getMessage());
            return null;
        }
    }

}
