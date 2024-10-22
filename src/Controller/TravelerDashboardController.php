<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/traveler', name: 'traveler_')]
#[IsGranted('ROLE_TRAVELER')]
class TravelerDashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function showProfile(): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
        ]);
    }
    #[Route('/reservations', name: 'reservations')]
    public function showReservations(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $userProfile = $user->getUserProfile();


        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            'traveler' => $user,
        ]);

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,

        ]);
    }
    #[Route('/reviews', name: 'reviews')]
    public function reviews(): Response
    {

        $reviews = $this->getUser()->getReviews();
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'reviews' => $reviews,
            'section' => 'avis',
            'userProfile' => $userProfile,
        ]);
    }
}
