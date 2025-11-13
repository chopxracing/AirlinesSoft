<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function show($city = 'Moscow')
    {
        $api_key = env('OPENWEATHER_API_KEY');
        $url = "https://api.openweathermap.org/data/2.5/weather";

        $response = Http::get($url, [
            'q' => $city,
            'appid' => $api_key,
            'units' => 'metric',
            'lang' => 'ru',
        ]);

        if ($response->failed()) {
            return response('', 500);
        }
        $data = $response->json();
        
    }

}
