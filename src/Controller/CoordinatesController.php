<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GeocoderManager;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
    /**
     * @Route(path="/custom", name="custom")
     * @param Request $request
     * @return Response
     */
    public function myNewAction(Request $request, GeocoderManager $geocoderManager): Response
    {
        $country = $request->get('country', 'lithuania');
        $city = $request->get('city', 'vilnius');
        $street = $request->get('street', 'jasinskio 16');
        $postcode = $request->get('postcode', '01112');

        $address = new Address($country, $city, $street, $postcode);

        $coordinates = $geocoderManager->geocode($address);

        return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }
}
