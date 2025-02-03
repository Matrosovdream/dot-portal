<?php
namespace App\Mixins\Converters;

use GuzzleHttp\Client;

Class ExchangeRateConverter
{

    protected $apiUrl;
    protected $apiKey;
    protected $client;

    public function __construct()
    {

        $this->apiUrl = 'https://v6.exchangerate-api.com/v6/';
        $this->apiKey = env('EXCHANGE_RATE_API_KEY');
        $this->client = new Client();
    }

    public function convert(string $fromCurrency, string $toCurrency, float $amount): float
    {

        // Params
        $apiUrl = $this->apiUrl;
        $apiKey = $this->apiKey;
        $client = new Client();

        // Build the API URL
        $url = $apiUrl . $apiKey . '/latest/' . $fromCurrency;

        // Make a request to the API
        $response = $client->get($url);

        // Process the response
        $data = $this->processResponse( $response );

        // Get the exchange rate and return
        $rate = $data['conversion_rates'][$toCurrency];
        return $amount * $rate;

    }

    protected function processResponse($response)
    {
        // Parse the response
        return json_decode($response->getBody()->getContents(), true);

    }

}