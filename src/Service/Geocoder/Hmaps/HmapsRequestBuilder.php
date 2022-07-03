<?php

namespace App\Service\Geocoder\Hmaps;

class HmapsRequestBuilder
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
                'qq' => implode(';', ["country={$country}", "city={$city}", "street={$street}", "postalCode={$postcode}"]),
                'apiKey' => $this->apiKey
            ]
        ];
    }
}
