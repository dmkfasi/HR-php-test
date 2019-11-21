<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp as Guzzle;

class WeatherYandexStream extends Model
{
    // HTTP client library to fetch data with
    private $_client = null;

    // HTTP method to fetch data with, defined in weather.php settings file
    private $_http_method = '';

    // URI to fetch data from, defined in weather.php settings file
    private $_uri = '';

    // Empty file content.
    private $_json = null; 

    // Common structures from Yandex JSON response stream
    public $info = null;
    public $fact = null;
    public $forecasts = null;

    public function __construct() {
      $this->_http_method = config('weather.yandex.method');
      $this->_uri = config('weather.yandex.uri');

      $this->_client = new Guzzle\Client();
    }

    /**
     *  Composes HTTP query array according to config settings (weather.php)
     * 
     * @return array
     **/
    private function _composeQuery()
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
    private function _composeHeaders()
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
          $response = $this->_client->request(
            $this->_http_method, 
            $this->_uri, [
                'query' => $this->_composeQuery(),
                'headers' => $this->_composeHeaders()
            ],
        );

        // Collect contents
        $json = $response->getBody()->getContents();

        if (!blank($json)) {
            $this->_json = $json;
        } else {
            throw new Exception('Unable to fetch JSON file from the URL specified.');
        }

      } catch (\GuzzleHttp\Exception\ClientException $e) {
          throw new \Exception('Unable to fetch fresh data: ' . $e->getMessage());
      } catch (\GuzzleHttp\Exception\RequestException $e) {
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
        $json = json_decode($this->_json, true);

        // Make sure there is no error parsing JSON content, otherwise panic!
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                'Unable to parse JSON content: ' . json_last_error_msg()
            );
        }

        return $json;
    }
}