<?php

namespace App\ValueObject;

class ResolvedAddress
{
    private Address $address;
    private ?Coordinates $coordinates;

    public function __construct(Address $address, ?Coordinates $coordinates)
    {
        $this->address = $address;
        $this->coordinates = $coordinates;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getCoordinates(): ?Coordinates
    {
        return $this->coordinates;
    }
}
