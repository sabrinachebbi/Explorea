<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Enum\statusResv;
use App\Repository\AccommodationRepository;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/host', name: 'host_')]
#[IsGranted('ROLE_HOST')]
class HostDashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();

        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'profile',  // Par défaut, on affiche le profil
            'userProfile' => $userProfile,
        ]);
    }

    #[Route('/accommodations', name: 'accommodations')]
    public function showAccommodations(AccommodationRepository $accommodationRepository): Response
    {
        $host = $this->getUser();
        $accommodations = $accommodationRepository->findBy(['host' => $host]);
        $userProfile = $host->getUserProfile();

        return $this->render('host_dashboard/dashboard.html.twig', [
            'userProfile' => $userProfile,
            'section' => 'accommodations',
            'accommodations' => $accommodations,
        ]);
    }

    #[Route('/activities', name: 'activities')]
    public function showActivities(ActivityRepository $activitiesRepository): Response
    {
        $host = $this->getUser();
        $activities = $activitiesRepository->findBy(['host' => $host]);
        $userProfile = $host->getUserProfile();

        return $this->render('host_dashboard/dashboard.html.twig', [
            'userProfile' => $userProfile,
            'section' => 'activities',
            'activities' => $activities,
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function showProfile(): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();

        return $this->render('host_dashboard/dashboard.html.twig', [
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

        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,

        ]);
    }


    #[Route('/notification', name: 'notification')]
    public function showNotifications(EntityManagerInterface $entityManager): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();// L'utilisateur connecté (l'hôte)
        $notifications = $entityManager->getRepository(Notification::class)->findBy([
            'user' => $host,
            'isRead' => false,
        ]);


        return $this->render('host_dashboard/dashboard.html.twig', [
            'notifications' => $notifications,
            'userProfile' => $userProfile,
            'section' => 'notification',
        ]);
    }


    #[Route('notification/confirm/{id}', name: 'confirm_host')]
    public function confirmReservationByHost(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();  // Récupérer l'utilisateur connecté

        // Vérifier si l'utilisateur connecté est bien l'hôte de l'hébergement
        if ($reservation->getAccommodation()->getHost()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à approuver cette réservation.');
        }

        // Confirmer la réservation
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::CONFIRM]);
        if ($status) {
            $reservation->setStatus($status);
            $reservation->setDateModification(new \DateTimeImmutable());
            $entityManager->flush();

            // Marquer les notifications associées à cette réservation comme lues
            $notifications = $entityManager->getRepository(Notification::class)->findBy([
                'reservation' => $reservation,
                'isRead' => false,
            ]);

            foreach ($notifications as $notification) {
                $notification->setRead(true);  // Marquer comme lue
            }

            $entityManager->flush();  // Sauvegarder les changements

            // Message flash de succès
            $this->addFlash('success', 'Réservation approuvée avec succès.');
        }

        return $this->redirectToRoute('host_notification');
    }


    #[Route('notification/cancel/{id}', name: 'cancel_host')]
    public function cancelReservationByHost(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();  // Récupérer l'utilisateur connecté

        // Vérifier si l'utilisateur connecté est bien l'hôte de l'hébergement
        if ($reservation->getAccommodation()->getHost()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à annuler cette réservation.');
        }

        // Annuler la réservation
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::CANCELLED]);
        if ($status) {
            $reservation->setStatus($status);
            $reservation->setDateModification(new \DateTimeImmutable());
            $entityManager->flush();

            // Marquer les notifications associées à cette réservation comme lues
            $notifications = $entityManager->getRepository(Notification::class)->findBy([
                'reservation' => $reservation,
                'isRead' => false,
            ]);

            foreach ($notifications as $notification) {
                $notification->setRead(true);  // Marquer comme lue
            }

            $entityManager->flush();  // Sauvegarder les changements

            // Message flash de succès
            $this->addFlash('warning', 'Réservation annulée avec succès.');
        }

        return $this->redirectToRoute('host_notification');
    }
}


