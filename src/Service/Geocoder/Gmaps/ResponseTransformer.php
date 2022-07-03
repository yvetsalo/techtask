<?php

namespace App\Service\Geocoder\Gmaps;

use App\ValueObject\Coordinates;

class ResponseTransformer
{
    public function transformToCoordinates(string $content): ?Coordinates
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['results'])) {
            return null;
        }

        if (count($data['results']) === 0) {
            return null;
        }

        $firstResult = $data['results'][0];

        if ($firstResult['geometry']['location_type'] !== 'ROOFTOP') {
            return null;
        }

        return new Coordinates(
            $firstResult['geometry']['location']['lat'],
            $firstResult['geometry']['location']['lng']
        );
    }
}
