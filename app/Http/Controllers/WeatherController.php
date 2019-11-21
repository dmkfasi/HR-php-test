<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index() {

        try {
            $ws = new \App\WeatherYandexStream();
            $ws->fetch();
            $weather = $ws->ingress();

            if (\View::exists('weather.yandex')) {
                return view('weather.yandex', [ 'weather' => $weather]);
            } else {
                // TODO Log error
                abort(404, __('No suitable view blade found.'));
            }
        } catch (\Exception $e) {
            abort(503, $e->getMessage());
        }
    }
}
