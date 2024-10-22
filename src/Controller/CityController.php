<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/get-cities-by-country/{countryId}', name: 'get_cities_by_country')]
    public function getCitiesByCountry(int $countryId, CityRepository $cityRepository): JsonResponse
    {
        $cities = $cityRepository->findBy(['country' => $countryId]);

        $response = [];
        foreach ($cities as $city) {
            $response['cities'][] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
            ];
        }

        return new JsonResponse($response);
    }
}
