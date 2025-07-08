<?php

namespace App\Mixins\Integrations;

class TranspGovAPI {

    protected $apiKey;
    protected $baseUrl = 'https://api.transpgov.com/v1/';

    public function __construct()
    {
        $this->apiKey = config('services.transpgov.api_key');
    }

}