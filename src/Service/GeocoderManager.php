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
        if ($cachedResolvedAddress = $this->resolvedAddressRepository->getByAddress($address)) {
            return $cachedResolvedAddress->getCoordinates();
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
