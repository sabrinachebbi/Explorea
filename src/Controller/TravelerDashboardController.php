<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function showReservations(Request $request ,EntityManagerInterface $entityManager,NotificationRepository $notificationRepository, PaginatorInterface $paginator ): Response
    {

        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        $page = $request->query->getInt('page', 1);
        $limit = 6;
        $unreadNotifications = $notificationRepository->findBy([
            'user' => $user,
            'isRead' => false,
        ]);


        $query = $entityManager->getRepository(Reservation::class)->findBy([
            'traveler' => $user,
        ]);
        $reservations = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,
            'unreadNotifications' => count($unreadNotifications),

        ]);
    }

    #[Route('/reviews', name: 'reviews')]
    public function reviews(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();

        // Récupérer toutes les réservations de l'utilisateur
        $reservations = $user->getReservations();

        // Extraire les avis de chaque réservation
        $reviews = [];
        foreach ($reservations as $reservation) {
            if ($reservation->getReviews()) { // Vérifie s'il y a un avis associé
                $reviews[] = $reservation->getReviews();
            }
        }

        // Paginer les avis
        $page = $request->query->getInt('page', 1);
        $limit = 5; // Nombre d'avis par page
        $paginatedReviews = $paginator->paginate(
            $reviews, // Le tableau d'avis
            $page,
            $limit
        );

        return $this->render('traveler_dashboard/dashboard.html.twig', [
            'section' => 'avis',
            'reviews' => $paginatedReviews,
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
