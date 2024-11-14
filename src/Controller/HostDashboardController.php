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
    public function showReservations(EntityManagerInterface $entityManager, PaginatorInterface $paginator, NotificationRepository $notificationRepository, Request $request): Response
    {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        $page = $request->query->getInt('page', 1);
        $limit = 6;

        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;

        // Créez la requête Doctrine pour obtenir les réservations de l'utilisateur
        $query = $entityManager->getRepository(Reservation::class)->createQueryBuilder('r')
            ->where('r.traveler = :user')
            ->setParameter('user', $user)
            ->getQuery();

        // Utilisez le paginator pour paginer les résultats
        $reservations = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return $this->render('host_dashboard/dashboard.html.twig', [
            'section' => 'reservations',
            'reservations' => $reservations,
            'userProfile' => $userProfile,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }



    #[Route('/notification', name: 'notification')]
    public function showNotifications(
        NotificationRepository $notificationRepository,Request $request, PaginatorInterface $paginator,
           ): Response {
        $page = $request->query->getInt('page', 1);
        $limit = 6;
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();


        $query = $notificationRepository->createQueryBuilder('n')
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery();

        // Utilisez le paginator pour paginer les résultats
        $notifications = $paginator->paginate(
            $query,
            $page,
            $limit
        );

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
        // Récupération des informations du voyageur
        $firstName = $reservation->getTraveler()->getUserProfile()->getFirstName(); // Prénom du voyageur
        $status = strtolower($reservation->getStatus()->getStatus()->value); // Statut (confirmée ou annulée)

        // Vérifier si la réservation concerne un hébergement ou une activité
        if ($reservation->getAccommodation()) {
            // Si c'est une réservation d'hébergement
            $title = $reservation->getAccommodation()->getTitle(); // Nom de l'hébergement
            $city = $reservation->getAccommodation()->getCity()->getName(); // Ville de l'hébergement
        } elseif ($reservation->getActivities()->count() > 0) {
            // Si c'est une réservation d'activité
            $activity = $reservation->getActivities()->first(); // Prendre la première activité
            $title = $activity->getTitle(); // Nom de l'activité
            $city = $activity->getCity()->getName(); // Ville de l'activité
        } else {
            throw new \LogicException('La réservation n\'est associée ni à un hébergement ni à une activité.');
        }

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

        // Vérifier si l'utilisateur est bien l'hôte de l'hébergement ou de l'activité
        if ($reservation->getAccommodation()) {
            if ($reservation->getAccommodation()->getHost()->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à approuver cette réservation.');
            }
        } elseif ($reservation->getActivities()->count() > 0) {
            $isHost = false;
            foreach ($reservation->getActivities() as $activity) {
                if ($activity->getHost()->getId() === $user->getId()) {
                    $isHost = true;
                    break;
                }
            }
            if (!$isHost) {
                throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à approuver cette réservation.');
            }
        } else {
            throw $this->createNotFoundException('Cette réservation n\'est associée ni à un hébergement ni à une activité.');
        }

        // Confirmer la réservation
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::CONFIRM]);
        if ($status) {
            $reservation->setStatus($status);
            $reservation->setDateModification(new \DateTimeImmutable());
            $entityManager->flush();

            $hostNotification = $notificationRepository->findOneBy(['reservation' => $reservation, 'user' => $user]);
            if ($hostNotification) {
                $hostNotification->setRead(true);  // Marquer comme lue
                $entityManager->flush();
            }

            $this->addFlash('success', 'Réservation approuvée avec succès.');
            $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);
        }

        // Envoi de l'email de confirmation au voyageur
        $this->envoyerEmailAuVoyageur($reservation, 'Réservation Confirmée');

        return $this->redirectToRoute('host_notification', [
            'unreadNotifications' => $unreadNotifications ?? 0
        ]);
    }

    #[Route('notification/cancel/{id}', name: 'cancel')]
    public function cancelReservationByHost(Reservation $reservation, EntityManagerInterface $entityManager, NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est bien l'hôte de l'hébergement ou de l'activité
        if ($reservation->getAccommodation()) {
            if ($reservation->getAccommodation()->getHost()->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à annuler cette réservation.');
            }
        } elseif ($reservation->getActivities()->count() > 0) {
            $isHost = false;
            foreach ($reservation->getActivities() as $activity) {
                if ($activity->getHost()->getId() === $user->getId()) {
                    $isHost = true;
                    break;
                }
            }
            if (!$isHost) {
                throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à annuler cette réservation.');
            }
        } else {
            throw $this->createNotFoundException('Cette réservation n\'est associée ni à un hébergement ni à une activité.');
        }

        // Annuler la réservation
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::CANCELLED]);
        if ($status) {
            $reservation->setStatus($status);
            $reservation->setDateModification(new \DateTimeImmutable());
            $entityManager->flush();

            $notification = $notificationRepository->findOneBy(['reservation' => $reservation, 'user' => $user]);
            if ($notification) {
                $notification->setRead(true);
                $entityManager->flush();
            }

            $this->addFlash('warning', 'Réservation annulée avec succès.');
            $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);
        }

        // Envoi de l'email d'annulation au voyageur
        $this->envoyerEmailAuVoyageur($reservation, 'Réservation Annulée');

        return $this->redirectToRoute('host_notification', [
            'unreadNotifications' => $unreadNotifications ?? 0
        ]);
    }

}


