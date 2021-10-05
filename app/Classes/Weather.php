<?php

namespace App\Classes;

use \App\Interfaces\Weather as WeatherInterface;

class Weather implements WeatherInterface
{
    private $weatherApi;

    public function __construct(WeatherInterface $weatherApi)
    {
        $this->weatherApi = $weatherApi;
    }

    /**
     * @return string
     */
    public function getTemperature(): string
    {
        return sprintf('%d ℃', $this->weatherApi->getTemperature());
    }

}
