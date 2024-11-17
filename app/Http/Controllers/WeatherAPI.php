<?php

namespace App\Http\Controllers;

use Cache;
use GuzzleHttp\Client;
use Http;

trait WeatherAPI
{
    public function WeatherAPI($method = "get", $endpoint, $params = [])
    {
        //$client = new Client();

        $url = config("services.third_party.base_url") . "" . $endpoint;


        $params = array_merge($params, ["key" => config("services.third_party.api_key")]);

        $response = match ($method) {
            'post' => Http::post($url, $params),
            default => Http::get($url, $params),
        };

        $json = json_decode($response->getBody(), true);


        return $this->WeatherResponse($response->getStatusCode(), "from api", $json);
    }

    public function WeatherResponse($status, $message, $data = null, $errors = null)
    {
        response([
            'status' => $status,
            'message' => $message,
            $data != null ? 'data' : 'errors' => $data != null ? $data : $errors,
        ], $status);
    }

    public function caching($endpoint, $data)
    {
        $cached = Cache::remember(
            $endpoint,
            10,
            function () use ($data) {
                $var = [
                    'cached' => 'success',
                    'data' => $data
                ];
                return $var;
            }
        );
        return response([
            'status' => 200,
            'message' => "from redis",
            "data" => $cached,
        ], 200);
    }
}
