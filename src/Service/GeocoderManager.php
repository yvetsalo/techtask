<?php

namespace App\Service;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use App\Service\Geocoder\GeocoderInterface;

class GeocoderManager
{
    /**
     * @var iterable<GeocoderInterface>
     */
    private iterable $geocoders;

    private ResolvedAddressRepository $resolvedAddressRepository;

    /**
     * @param iterable<GeocoderInterface> $geocoders
     */
    public function __construct(iterable $geocoders, ResolvedAddressRepository $resolvedAddressRepository)
    {
        $this->geocoders = $geocoders;
        $this->resolvedAddressRepository = $resolvedAddressRepository;
    }

    /**
     * @param Address $address
     * @return Coordinates|null
     */
    public function geocode(Address $address): ?Coordinates
    {
        if ($cachedAddress = $this->resolvedAddressRepository->getByAddress($address)) {
            if (is_null($cachedAddress->getLng()) && is_null($cachedAddress->getLat())) {
                return null;
            }

            return new Coordinates($cachedAddress->getLat(), $cachedAddress->getLng());
        }

        $coordinates = null;

        foreach ($this->geocoders as $geocoder) {
            if ($coordinates = $geocoder->geocode($address)) {
                break;
            }
        }

        $this->resolvedAddressRepository->saveResolvedAddress($address, $coordinates);

        return $coordinates;
    }
}
