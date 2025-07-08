<?php

namespace App\Mixins\Integrations;

class TranspGovAPI {

    protected $apiKey;
    protected $baseUrl = 'https://api.transpgov.com/v1/';
    public $filter = [];

    public function __construct()
    {
        $this->apiKey = config('services.transpgov.api_key');
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }

}