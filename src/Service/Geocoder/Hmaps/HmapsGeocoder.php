<?php

namespace App\Service\Geocoder\Hmaps;

use App\Service\Geocoder\HttpClientAwareGeocoder;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class HmapsGeocoder extends HttpClientAwareGeocoder
{
    private HmapsRequestBuilder $requestBuilder;
    private HmapsResponseTransformer $responseTransformer;
    private const HMAPS_ENDPOINT = 'https://geocode.search.hereapi.com/v1/geocode';

    public function __construct(HmapsRequestBuilder $requestBuilder, HmapsResponseTransformer $responseTransformer)
    {
        $this->requestBuilder = $requestBuilder;
        $this->responseTransformer = $responseTransformer;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $request = $this->requestBuilder->build(
            $address->getCountry(),
            $address->getCity(),
            $address->getStreet(),
            $address->getPostcode()
        );

        $response = $this->getClient()->get(self::HMAPS_ENDPOINT, $request);

        return $this->responseTransformer->transformToCoordinates($response->getBody()->getContents());
    }
}
