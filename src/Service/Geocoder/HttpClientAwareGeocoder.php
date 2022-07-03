<?php

namespace App\Service\Geocoder;

use App\Service\GeocoderInterface;
use GuzzleHttp\Client;

abstract class HttpClientAwareGeocoder implements GeocoderInterface
{
    private ?Client $client = null;

    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}
