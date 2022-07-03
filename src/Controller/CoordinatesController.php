<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GeocoderManager;
use App\Service\ValueObjectFactory\AddressFactory;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
    /**
     * @Route(path="/coordinates", name="geocode")
     * @param Request $request
     * @return Response
     */
    public function coordinatesAction(Request $request, GeocoderManager $geocoderManager, AddressFactory $addressFactory): Response
    {
        $address = $addressFactory->fromArray([
            'country' => $request->get('country', 'lt'),
            'city' => $request->get('city', 'vilnius'),
            'street' => $request->get('street', 'jasinskio 16'),
            'postcode'=> $request->get('postcode', '01112'),
        ]);

        $coordinates = $geocoderManager->geocode($address);

        return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }
}
