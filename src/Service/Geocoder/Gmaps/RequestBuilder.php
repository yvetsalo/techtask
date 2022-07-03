<?php

namespace App\Service\Geocoder\Gmaps;

class RequestBuilder
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function build(string $country, string $city, string $street, string $postcode): array
    {
        return [
            'query' => [
                'address' => $street,
                'components' => implode('|', ["country:{$country}", "locality:{$city}", "postal_code:{$postcode}"]),
                'key' => $this->apiKey
            ]
        ];
    }
}
