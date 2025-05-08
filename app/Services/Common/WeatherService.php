<?php

namespace App\Services\Common;

use GuzzleHttp\Client;

class WeatherService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('WEATHER_URL'),
            'timeout' => 5.0,
        ]);
    }

    public function getCurrentWeather(string $city): array
    {
        $response = $this->client->get('?', [
            'query' => [
                'q' => $city,
                'appid' => env('WEATHER_KEY'),
                'units' => 'metric',
                'lang' => 'ru'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
