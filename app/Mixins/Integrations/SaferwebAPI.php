<?php

namespace App\Mixins\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaferwebAPI
{
    protected string $baseUrl = 'https://saferwebapi.com/';
    protected string $apiKey;
    protected $routes = [
        'company.snapshot' => '/v2/usdot/snapshot/',
        'inspection.history' => 'v3/history/inspection/',
        'crash.history' => 'v3/history/crash/',
        'inspection.summary' => 'v3/history/inspection/',
        'history.all' => 'v3/history/everything/',
    ]; 

    public function __construct()
    {
        $this->apiKey = config('services.saferweb.api_key');
    }

    public function getCompanySnapshot(string $usdot): array|null
    {
        return $this->request("{$this->routes['company.snapshot']}{$usdot}");
    }

    public function getInspectionHistory(string $usdot): array|null
    {
        return $this->request("{$this->routes['inspection.history']}{$usdot}");
    }

    public function getCrashHistory(string $usdot): array|null
    {
        return $this->request("{$this->routes['crash.history']}{$usdot}");
    }

    public function getInspectionSummary(string $usdot): array|null
    {
        return $this->request("{$this->routes['inspection.summary']}{$usdot}");
    }

    public function getHistoryAll(string $usdot): array|null
    {
        return $this->request("{$this->routes['history.all']}{$usdot}");
    }

    protected function request(string $endpoint): array|null
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->timeout(30)->get($this->baseUrl . $endpoint);

            if ($response->status() === 200) {
                return $response->json();
            } else {
                $error = [
                    'error' => true,
                    'status' => $response->status(),
                    'message' => $response->json('message') ?? $response->body(),
                ];

                Log::error('SaferWeb API error', [
                    'url' => $endpoint,
                    'status' => $error['status'],
                    'message' => $error['message'],
                ]);

                return $error;
            }
        } catch (\Exception $e) {
            $error = [
                'error' => true,
                'status' => 500,
                'message' => $e->getMessage(),
            ];

            Log::error('SaferWeb API Exception', $error);

            return $error;
        }
    }


}
