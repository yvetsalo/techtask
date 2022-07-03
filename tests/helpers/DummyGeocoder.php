<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Service\GeocoderInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class DummyGeocoder implements GeocoderInterface
{
    public function geocode(Address $address): ?Coordinates
    {
        return new Coordinates(55.90742079144914, 21.135541627577837);
    }
}
