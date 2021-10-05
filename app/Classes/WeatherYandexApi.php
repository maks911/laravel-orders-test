<?php

namespace App\Classes;

use \App\Interfaces\Weather as WeatherInterface;

class WeatherYandexApi implements WeatherInterface
{
    private $apiUrl;
    private $token;
    private $data;

    /**
     *
     */
    public function __construct()
    {
        $this->apiUrl = env('WEATHER_API_URL', '');
        $this->token = env('WEATHER_API_TOKEN', '');
        $this->data = $this->getResponse();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemperature(): string
    {
        if (isset($this->data['fact'])) {
            return (float)$this->data['fact']['temp'];
        }

        return '';
    }

    /**
     * @return array
     */
    private function getResponse(): array
    {
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'X-Yandex-API-Key' => $this->token
            ]
        ]);
        $request = $client->get($this->apiUrl);
        $response = $request->getBody()->getContents();
        return $this->isJson($response) ? json_decode($response, true) : [];
    }

    /**
     * @param $string
     * @return bool
     */
    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
