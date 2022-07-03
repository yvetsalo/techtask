<?php

namespace App\Service\Geocoder\Gmaps;

use App\Service\Geocoder\HttpClientAwareGeocoder;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GmapsGeocoder extends HttpClientAwareGeocoder
{
    private GmapsRequestBuilder $requestBuilder;
    private GmapsResponseTransformer $responseTransformer;
    private const GMAPS_ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function __construct(GmapsRequestBuilder $requestBuilder, GmapsResponseTransformer $gmapsResponseTransformer)
    {
        $this->requestBuilder = $requestBuilder;
        $this->responseTransformer = $gmapsResponseTransformer;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $request = $this->requestBuilder->build(
            $address->getCountry(),
            $address->getCity(),
            $address->getStreet(),
            $address->getPostcode()
        );

        $response = $this->getClient()->get(self::GMAPS_ENDPOINT, $request);

        return $this->responseTransformer->transformToCoordinates($response->getBody()->getContents());
    }
}
