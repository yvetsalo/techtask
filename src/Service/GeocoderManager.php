<?php

namespace App\Service;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderManager
{
    /**
     * @var iterable<GeocoderInterface>
     */
    private iterable $geocoders;

    /**
     * @param iterable<GeocoderInterface> $geocoders
     */
    public function __construct($geocoders)
    {
        $this->geocoders = $geocoders;
    }

    /**
     * @param Address $address
     * @return Coordinates|null
     */
    public function geocode(Address $address): ?Coordinates
    {
        foreach ($this->geocoders as $geocoder) {
            if ($result = $geocoder->geocode($address)) {
                return $result;
            }
        }

        return null;
    }
}
