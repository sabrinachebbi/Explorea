<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index( CountryRepository $countryRepository,NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;

        $countries = $countryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'countries' => $countries,
            'unreadNotifications' => $unreadNotifications,
            'notifications' => $unreadNotifications,

        ]);
    }
}
