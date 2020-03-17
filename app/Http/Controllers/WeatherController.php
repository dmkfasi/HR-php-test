<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;

class WeatherController extends Controller
{
    public function index(WeatherService $weatherService) {

        try {
            $weatherData = $weatherService->retrieveTemperature();

            if (\View::exists('weather.yandex')) {
                return view('weather.yandex', [ 'weather' => $weatherData]);
            } else {
                // TODO Log error
                abort(404, __('No suitable view blade found.'));
            }
        } catch (\Exception $e) {
            abort(503, $e->getMessage());
        }
    }
}
