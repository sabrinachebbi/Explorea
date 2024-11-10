<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Enum\statusResv;
use App\Repository\AccommodationRepository;
use App\Repository\ActivityRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/host', name: 'host_')]
#[IsGranted('ROLE_HOST')]
class HostDashboardController extends AbstractController
{
    private $mailer;
    // Injection du service MailerInterface dans le constructeur
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
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


        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
            'unreadNotifications' => count($unreadNotifications),
        ]);
    }

    #[Route('/accommodations', name: 'accommodations')]
    public function showAccommodations(AccommodationRepository $accommodationRepository,NotificationRepository $notificationRepository): Response
    {
        $host = $this->getUser();
        $accommodations = $accommodationRepository->findBy(['host' => $host]);
        $userProfile = $host->getUserProfile();
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;


        return $this->render('host_dashboard/dashboard.html.twig', [
            'userProfile' => $userProfile,
            'section' => 'accommodations',
            'accommodations' => $accommodations,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    #[Route('/activities', name: 'activities')]
    public function showActivities(ActivityRepository $activitiesRepository,NotificationRepository $notificationRepository): Response
    {
        $host = $this->getUser();
        $activities = $activitiesRepository->findBy(['host' => $host]);
        $userProfile = $host->getUserProfile();
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;

        return $this->render('host_dashboard/dashboard.html.twig', [
            'userProfile' => $userProfile,
            'section' => 'activities',
            'activities' => $activities,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function showProfile(NotificationRepository $notificationRepository): Response
    {
        $host = $this->getUser();
        $userProfile = $host->getUserProfile();
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;

        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'profile',
            'userProfile' => $userProfile,
            'unreadNotifications' => $unreadNotifications
        ]);
    }
    #[Route('/reservations', name: 'reservations')]
    public function showReservations(EntityManagerInterface $entityManager,NotificationRepository $notificationRepository): Response
    {

        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;


        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            'traveler' => $user,
        ]);

        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,
            'unreadNotifications' => $unreadNotifications

        ]);
    }


    #[Route('/notification', name: 'notification')]
    public function showNotifications(
        NotificationRepository $notificationRepository
    ): Response {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();


        $notifications = $notificationRepository->findBy([
            'user' => $user,
            'isRead' => false
        ], ['createdAt' => 'DESC']);

        // Compter les notifications non lues
        $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);

        return $this->render('host_dashboard/dashboard.html.twig', [
            'notifications' => $notifications,
            'userProfile' => $userProfile,
            'section' => 'notification',
            'unreadNotifications' => $unreadNotifications,
        ]);
    }

    private function envoyerEmailAuVoyageur(Reservation $reservation, string $sujet): void
    {
        // Récupération des informations pour le contenu de l'email
        $firstName = $reservation->getTraveler()->getUserProfile()->getFirstName(); // Prénom du voyageur
        $title = $reservation->getAccommodation()->getTitle(); // Nom de l'hébergement
        $city = $reservation->getAccommodation()->getCity()->getName(); // Ville de l'hébergement
        $status = strtolower($reservation->getStatus()->getStatus()->value); // Statut (confirmée ou annulée)

        // Construction du contenu de l'email
        $contenu = "Bonjour " . $firstName . ",\n\n";
        $contenu .= "Votre réservation pour \"" . $title . "\" à " . $city . " a été " . $status . ".\n\n";
        $contenu .= "Merci d'utiliser Exploréa !";

        // Création de l'email
        $emailMessage = (new Email())
            ->from('no-reply@explorea.com')
            ->to($reservation->getTraveler()->getEmail())
            ->subject($sujet)
            ->text($contenu);

        // Envoi de l'email
        $this->mailer->send($emailMessage);
    }


    #[Route('notification/confirm/{id}', name: 'confirm')]
    public function confirmReservationByHost(Reservation $reservation, EntityManagerInterface $entityManager, NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();

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


            $hostNotification = $notificationRepository->findOneBy(['reservation' => $reservation, 'user' => $this->getUser()]);
            if ($hostNotification) {
                $hostNotification->setRead(true);  // Marquer comme lue
                $entityManager->flush();
            }

            // Message flash de succès
            $this->addFlash('success', 'Réservation approuvée avec succès.');

            // Récupérer le nombre de notifications non lues
            $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);
        }
        // Envoi de l'email de confirmation au voyageur
        $this->envoyerEmailAuVoyageur(
            $reservation,
            'Réservation Confirmée'
        );

        $this->addFlash('success', 'Réservation confirmée.');

        // Redirection avec unreadNotifications
        return $this->redirectToRoute('host_notification', [
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    #[Route('notification/cancel/{id}', name: 'cancel')]
    public function cancelReservationByHost(Reservation $reservation, EntityManagerInterface $entityManager,NotificationRepository $notificationRepository): Response
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

            $notification = $notificationRepository->findOneBy(['reservation' => $reservation, 'user' => $this->getUser()]);
            if ($notification) {
                $notification->setRead(true);
            }
            $entityManager->flush();
            // Message flash de succès
            $this->addFlash('warning', 'Réservation annulée avec succès.');
            // Récupérer le nombre de notifications non lues
            $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);
        }
        // Envoi de l'email de confirmation au voyageur
        $this->envoyerEmailAuVoyageur(
            $reservation,
            'Réservation Annulée'
        );

        return $this->redirectToRoute('host_notification', [
            'unreadNotifications' => $unreadNotifications
        ]);
    }
}


