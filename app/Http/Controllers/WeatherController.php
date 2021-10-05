<?php

namespace App\Http\Controllers;

use App\Classes\Weather;
use App\Classes\WeatherYandexApi;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        $weatherApi = new WeatherYandexApi();
        $weather = new Weather($weatherApi);
        $temperature = $weather->getTemperature();
        return view('pages.weather',compact('temperature'));
    }
}
