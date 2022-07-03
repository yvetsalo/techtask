<?php

namespace App\Service\Factory;

use App\ValueObject\Address;

class AddressFactory
{
    public function fromArray(array $data)
    {
        return new Address(
            $data['country'],
            $data['city'],
            $data['street'],
            $data['postcode'],
        );
    }
}
