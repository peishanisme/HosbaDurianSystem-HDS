<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getCurrentWeather()
    {
        // Your farm coordinates
        $lat = 2.1896;  
        $lon = 102.2501;

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relativehumidity_2m,precipitation,wind_speed_10m";

        $response = Http::get($url)->json();

        return $response['current'] ?? [];
    }
}
