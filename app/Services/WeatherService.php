<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class WeatherService
{
    // HTTP client library to fetch data with
    private $client = null;

    // HTTP method to fetch data with, defined in weather.php settings file
    private $http_method = '';

    // URI to fetch data from, defined in weather.php settings file
    private $uri = '';

    // Empty file content.
    private $json = null; 

    // Common structures from Yandex JSON response stream
    public $info = null;
    public $fact = null;
    public $forecasts = null;

    public function __construct(Client $client) {
      $this->http_method = config('weather.yandex.method');
      $this->uri = config('weather.yandex.uri');

      $this->client = $client;
    }

    /**
     *  Composes HTTP query array according to config settings (weather.php)
     * 
     * @return array
     **/
    private function composeQuery()
    {
        return [
            'lat' => config('weather.yandex.lat'),
            'lon' => config('weather.yandex.lon'),
            'lang' => config('weather.yandex.lang')
        ];
    }

    /**
     *  Composes HTTP headers according to config settings (weather.php)
     * 
     * @return array
     **/
    private function composeHeaders()
    {
        return [
            config('weather.yandex.key_header_name') => config('weather.yandex.key'),
        ];
    }

    /**
     * 
     * 
     * @return boolean
     **/
    public function fetch()
    {
      // Download Weather data using GuzzleHttp library
      try {
          $response = $this->client->request(
            $this->http_method, 
            $this->uri, [
                'query' => $this->composeQuery(),
                'headers' => $this->composeHeaders()
            ],
        );

        // Collect contents
        $json = $response->getBody()->getContents();

        if (!blank($json)) {
            $this->json = $json;
        } else {
            throw new \Exception('Unable to fetch JSON file from the URL specified.');
        }

      } catch (ClientException $e) {
          throw new \Exception('Unable to fetch fresh data: ' . $e->getMessage());
      } catch (RequestException $e) {
          throw new \Exception('Unable to fetch fresh data: ' . $e->getMessage());
      }

      return true;
    }
    
    /**
     * Parses JSON data and gives it back
     * 
     * @return array
     * @throws Exception
     */
    public function ingress()
    {
        $json = json_decode($this->json, true);

        // Make sure there is no error parsing JSON content, otherwise panic!
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                'Unable to parse JSON content: ' . json_last_error_msg()
            );
        }

        return $json;
    }

    public function retrieveTemperature()
    {
        $this->fetch();

        return $this->ingress();
    }
}