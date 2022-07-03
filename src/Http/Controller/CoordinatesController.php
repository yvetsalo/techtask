<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Http\ResponseBuilder\CoordinatesBuilder;
use App\Service\GeocoderManager;
use App\Service\Factory\AddressFactory;
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
    public function coordinatesAction(Request $request, GeocoderManager $geocoderManager, AddressFactory $addressFactory, CoordinatesBuilder $builder): Response
    {
        $address = $addressFactory->fromArray([
            'country' => $request->get('country', 'lt'),
            'city' => $request->get('city', 'vilnius'),
            'street' => $request->get('street', 'jasinskio 16'),
            'postcode'=> $request->get('postcode', '01112'),
        ]);

        $coordinates = $geocoderManager->geocode($address);

        return $builder->toJsonResponse($coordinates);
    }
}
