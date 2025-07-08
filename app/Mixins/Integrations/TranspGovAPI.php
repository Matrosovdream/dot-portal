<?php

namespace App\Mixins\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranspGovAPI {

    protected $apiTitle =  'Transportation Government API';
    protected $apiKey;
    protected $baseUrl = 'https://data.transportation.gov/resource/';
    protected $routes = [
        'company.snapshot' => 'az4n-8mr2.json',
    ];
    public $filter = [];

    public function __construct()
    {
        $this->apiKey = config('services.transpgov.api_key') ?? '';
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }

    protected function prepareFilter(array $filter): string
    {
        $prepared = [];
        
        foreach ($filter as $key => $value) {
            
            switch ( $value['operator'] ?? '' ) {
                case 'IN':
                    $prepared[$key] = $key. ' IN ('.implode(',', $value['value']).')';
                    break;
                default:
                    $prepared[$key] = $value['value'];
            }

        }

        $filterRes = '$where='.implode(' AND ', $prepared);

        return $filterRes;

    }

    public function request(string $endpoint): array|null
    {

        $url = $this->baseUrl . $this->routes[ $endpoint ] . '?' . $this->prepareFilter($this->filter);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])
            ->timeout(30)
            ->get($url);

            // $this->prepareFilter( $this->filter ) 

            if ($response->status() === 200) {
                return $response->json();
            } else {
                $error = [
                    'error' => true,
                    'status' => $response->status(),
                    'message' => $response->json('message') ?? $response->body(),
                ];

                /*
                Log::error('Transportation Government error', [
                    'url' => $endpoint,
                    'status' => $error['status'],
                    'message' => $error['message'],
                ]);
                */

                return $error;
            }
        } catch (\Exception $e) {
            $error = [
                'error' => true,
                'status' => 500,
                'message' => $e->getMessage(),
            ];

            Log::error($this->apiTitle.' Exception', $error);

            return $error;
        }
    }

}