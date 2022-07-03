<?php

namespace App\Http\ResponseBuilder;

use App\ValueObject\Coordinates;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoordinatesBuilder
{
    public function toJsonResponse(?Coordinates $coordinates): JsonResponse
    {
        if (null === $coordinates) {
            return new JsonResponse([]);
        }

        return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }
}
