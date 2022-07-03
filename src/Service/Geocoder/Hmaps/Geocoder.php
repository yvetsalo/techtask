<?php

namespace App\Service\Geocoder\Hmaps;

use App\Service\Client;
use App\Service\GeocoderInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class Geocoder implements GeocoderInterface
{
    private RequestBuilder $requestBuilder;
    private ResponseTransformer $responseTransformer;
    private Client $client;
    private string $endpoint;

    public function __construct(
        RequestBuilder      $requestBuilder,
        ResponseTransformer $responseTransformer,
        Client              $client,
        string              $endpoint
    )
    {
        $this->requestBuilder = $requestBuilder;
        $this->responseTransformer = $responseTransformer;
        $this->client = $client;
        $this->endpoint = $endpoint;
    }

    public function geocode(Address $address): ?Coordinates
    {
        $request = $this->requestBuilder->build(
            $address->getCountry(),
            $address->getCity(),
            $address->getStreet(),
            $address->getPostcode()
        );

        $response = $this->client->get($this->endpoint, $request);

        return $this->responseTransformer->transformToCoordinates($response->getBody()->getContents());
    }
}
