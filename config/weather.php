<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    // Default env value for the key is only to avoid .env file modification
    'yandex' => [
        'method' => 'GET',
        'key_header_name' => 'X-Yandex-API-Key',
        'key' => env('WEATHER_YANDEX_KEY', '5b47f070-49a0-4911-8218-934d47af20df'),
        'uri' => 'https://api.weather.yandex.ru/v1/forecast',
        'lat' => '53.15',
        'lon' => '34.22',
        'lang' => 'ru_RU',
        'limit' => '1',
        'hours' => 'false',
        'extra' => 'false',
    ],

];
