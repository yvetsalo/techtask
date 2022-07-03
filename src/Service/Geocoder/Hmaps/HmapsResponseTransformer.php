<?php

namespace App\Service\Geocoder\Hmaps;

use App\ValueObject\Coordinates;

class HmapsResponseTransformer
{
    public function transformToCoordinates(string $content): ?Coordinates
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (count($data['items']) === 0) {
            return null;
        }

        $firstItem = $data['items'][0];

        if ($firstItem['resultType'] !== 'houseNumber') {
            return null;
        }

        return new Coordinates(
            $firstItem['position']['lat'],
            $firstItem['position']['lng'],
        );
    }
}
