<?php

namespace App\Controller\Administration;

use App\Repository\AccommodationRepository;
use App\Repository\ActivityRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin', name: 'admin_')]
class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        UserRepository $userRepository,
        ReservationRepository $reservationRepository,
        ActivityRepository $activityRepository,
        AccommodationRepository $accommodationRepository
    ): Response {
        // Récupérer les statistiques principales
        $userCount = $userRepository->count([]);
        $reservationCount = $reservationRepository->count([]);
        $activityCount = $activityRepository->count([]);
        $accommodationCount = $accommodationRepository->count([]);

        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'userCount' => $userCount,
            'reservationCount' => $reservationCount,
            'activityCount' => $activityCount,
            'accommodationCount' => $accommodationCount,
            'section' => 'default',
        ]);
    }
}
