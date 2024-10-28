<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\NotificationRepository;
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
    public function index(NotificationRepository $notificationRepository): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();
        // Récupérer les notifications non lues pour l'hôte
        $unreadNotifications = $notificationRepository->findBy([
            'user' => $host,
            'isRead' => false,
        ]);


        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
            'unreadNotifications' => count($unreadNotifications),
            'notifications' => $unreadNotifications,
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function showProfile(NotificationRepository $notificationRepository): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();
        // Récupérer les notifications non lues pour l'hôte
        $unreadNotifications = $notificationRepository->findBy([
            'user' => $host,
            'isRead' => false,
        ]);

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
            'unreadNotifications' => count($unreadNotifications),
        ]);
    }
    #[Route('/reservations', name: 'reservations')]
    public function showReservations(EntityManagerInterface $entityManager,NotificationRepository $notificationRepository): Response
    {

        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        $unreadNotifications = $notificationRepository->findBy([
            'user' => $user,
            'isRead' => false,
        ]);


        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            'traveler' => $user,
        ]);

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,
            'unreadNotifications' => count($unreadNotifications),

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
    #[Route('/notification', name: 'notification')]
    public function showTravelerNotifications(NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();
        $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);
        $notifications = $notificationRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('traveler_dashboard/notification.html.twig', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications
        ]);
    }
}
