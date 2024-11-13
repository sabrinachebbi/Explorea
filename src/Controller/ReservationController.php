<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Entity\Notification;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Enum\statusResv;
use App\Form\ReservationAccommodationFormType;
use App\Form\ReservationActivityFormType;
use App\Form\ReservationAccommodationType;
use App\Form\ReservationActivityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/reservation', name: 'reservation_')]
class ReservationController extends AbstractController
{

    #[Route('/accommodation/{id}', name: 'accommodation')]
    #[IsGranted('ROLE_TRAVELER')]
    public function AccommodationReservation(Accommodation $accommodation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $reservation->setAccommodation($accommodation);

        $form = $this->createForm(ReservationAccommodationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer le total en appelant la méthode calculateTotal
            $total = $reservation->calculateTotal();
            $reservation->setTotal($total);
            $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::PENDING]);

            if ($status) {
                $reservation->setStatus($status);
            }

            $reservation->setDateCreation(new \DateTimeImmutable());
            $reservation->setDateModification(new \DateTimeImmutable());
            $reservation->setTraveler($this->getUser());

            $entityManager->persist($reservation);
            $entityManager->flush();

            // Créer une notification pour l'hôte associé
            $host = $accommodation->getHost();
            $this->createNotificationForHost($host, $reservation, $entityManager);
            return $this->redirectToRoute('reservation_details', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reservationAccommodation.html.twig', [
            'reservationForm' => $form,
            'accommodation' => $accommodation,
            'total' => $form->isSubmitted() ? $reservation->calculateTotal() : 0,
        ]);
    }

    #[Route('/activity/{id}', name: 'activity')]
    #[IsGranted('ROLE_TRAVELER')]
    public function ActivityReservation(Request $request, EntityManagerInterface $entityManager, Activity $activity): Response
    {
        // Récupérer la ville de l'activité
        $city = $activity->getCity();
        if (!$city) {
            throw $this->createNotFoundException('La ville associée à cette activité n\'existe pas.');
        }

        // Créer une nouvelle réservation et ajouter l'activité
        $reservation = new Reservation();
        $reservation->addActivity($activity);
        $reservation->setTraveler($this->getUser());

        // Créer le formulaire pour la réservation d'activité avec l'option 'city'
        $form = $this->createForm(ReservationActivityFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir le statut de la réservation et les dates
            $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::PENDING]);
            $reservation->setStatus($status);
            $reservation->setDateCreation(new \DateTimeImmutable());
            $reservation->setDateModification(new \DateTimeImmutable());
            $reservation->setTotal($reservation->calculateTotal());

            // Sauvegarder la réservation et créer une notification pour l'hôte
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->createNotificationForHost($activity->getHost(), $reservation, $entityManager);

            // Rediriger vers la page de récapitulatif de l'activité
            return $this->redirectToRoute('reservation_details_activity', ['id' => $reservation->getId()]);
        }

        // Rendre la vue avec le formulaire et les détails de l'activité
        return $this->render('reservation/ReservationActivity.html.twig', [
            'reservationForm' => $form->createView(),
            'activity' => $activity,
            'total' => $form->isSubmitted() ? $reservation->calculateTotal() : 0,
        ]);
    }


    #[Route('/show/{id}', name: 'details')]
    #[IsGranted('ROLE_TRAVELER')]
    public function recap(Reservation $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationAccommodationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_confirm', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reservation_summary.html.twig', [
            'reservation' => $reservation,
            'form' => $form
        ]);
    }
    #[Route('/show/activity/{id}', name: 'details_activity')]
    #[IsGranted('ROLE_TRAVELER')]
    public function recapActivity(Reservation $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Formulaire simple pour la confirmation de la réservation d'activité
        $form = $this->createForm(ReservationActivityType::class, $reservation, [
            'city' => $reservation->getActivities()->first()->getCity(), // ou une autre façon d'obtenir la ville
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('reservation_confirm', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reservation_summary.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    private function createNotificationForHost($host, $reservation, EntityManagerInterface $entityManager): void
    {
        $notification = new Notification();
        $notification->setUser($host);
        $notification->setMessage('Une réservation a été effectuée sur votre annonce.');
        $notification->setReservation($reservation);
        $notification->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($notification);
        $entityManager->flush();
    }


        #[Route('/confirm/{id}', name: 'confirm')]
    public function confirmReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est le voyageur qui a fait la réservation
        if ($reservation->getTraveler() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à confirmer cette réservation.');
        }

        // Mettre à jour le statut de la réservation à "PENDING"
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::PENDING]);
        if ($status) {
            $reservation->setStatus($status);
            $reservation->setDateModification(new \DateTimeImmutable());
            $entityManager->flush();
        }

        // Message flash pour indiquer que la réservation est en attente
        $this->addFlash('success', 'Votre réservation est effectuée et en attente de confirmation');

        // Au lieu de rediriger, rendre la vue directement pour tester l'affichage des messages flash
        return $this->render('reservation/reservation_summary.html.twig', [
            'reservation' => $reservation,
        ]);
    }



    #[Route('cancel/{id}', name: 'cancel')]
    public function cancelReservationByUser(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur connecté est bien celui qui a effectué la réservation
        if ($reservation->getTraveler() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à annuler cette réservation.');
        }

        // Annuler la réservation
        $status = $entityManager->getRepository(ReservationStatus::class)->findOneBy(['status' => statusResv::CANCELLED]);
        $reservation->setStatus($status);
        $reservation->setDateModification(new \DateTimeImmutable());

        // Sauvegarder les modifications
        $entityManager->flush();
        $this->addFlash('warning', 'Vous avez annulé votre réservation');
//
        // Rediriger vers la page de liste des réservations ou autre page pertinente
        return $this->redirectToRoute('reservation_details', ['id' => $reservation->getId()]);
    }
    #[Route('/update/{id}', name: 'update')]
    #[IsGranted('ROLE_TRAVELER')]
    public function updateReservation(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si l'utilisateur connecté est le voyageur ayant réservé
        if ($reservation->getTraveler() !== $this->getUser()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier cette réservation.');
            return $this->redirectToRoute('host_reservations');
        }

        // Vérifier si le statut de la réservation est "en attente"
        if ($reservation->getStatus()->getStatus() !== statusResv::PENDING) {
            $this->addFlash('error', 'Vous ne pouvez modifier cette réservation que si elle est en attente.');
            return $this->redirectToRoute('host_reservations');
        }

        // Créer le formulaire avec les données actuelles de la réservation
        $form = $this->createForm(ReservationAccommodationFormType::class, $reservation);
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setDateModification(new \DateTimeImmutable());

            // Sauvegarder la réservation mise à jour dans la base de données
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été mise à jour avec succès.');

            return $this->redirectToRoute('host_reservations', ['id' => $reservation->getId()]);
        }

        // Afficher le formulaire de mise à jour avec les données actuelles
        return $this->render('reservation/reservation_update.html.twig', [
            'reservationForm' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

}

