<?php

namespace App\Controller;


use App\Form\AccommodationFilterType;
use App\Repository\AccommodationRepository;
use App\Repository\ActivityRepository;
use App\Repository\CountryRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/search', name: 'search_')]
class SearchController extends AbstractController
{
    #[Route('/result', name: 'result')]
    public function searchResults(Request $request, AccommodationRepository $accommodationRepo, ActivityRepository $activityRepo,NotificationRepository $notificationRepository): Response
    {
        $country = $request->query->get('destination');
        $type = $request->query->get('type');

        $accommodations = [];
        $activities = [];

        if ($type === 'accommodation' || empty($type)) {
            // Recherche par destination pour les hébergements
            $accommodations = $accommodationRepo->findByCountry($country);
        }

        if ($type === 'activity' || empty($type)) {
            // Recherche par destination pour les activités
            $activities = $activityRepo->findByCountry($country);
        }
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;


        return $this->render('search/SearchResult.html.twig', [
            'accommodations' => $accommodations,
            'activities' => $activities,
            'country' => $country,
            'type' => $type,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    #[Route('/countries', name: 'countries')]
    public function search(CountryRepository $countryRepository): Response
    {
        // Récupérer les pays disponibles
        $countries = $countryRepository->findAll();

        return $this->render('search/SearchBar.html.twig', [
            'countries' => $countries,
        ]);
    }

}
