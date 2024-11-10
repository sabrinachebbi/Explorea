<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Form\ReviewFormType;
use App\Repository\ReservationRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review', name: 'review_')]
#[IsGranted('ROLE_TRAVELER')]
class ReviewController extends AbstractController
{
    #[Route('/accommodation/{id}', name: 'accommodation')]
    public function AccommodationReview(
        Accommodation $accommodation,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $traveler = $this->getUser();

        // Vérifiez si le voyageur a une réservation pour cet hébergement
        $reservation = $reservationRepository->findOneBy([
            'accommodation' => $accommodation,
            'traveler' => $traveler,
        ]);

        if (!$reservation) {
            $this->addFlash('error', 'Vous devez réserver cet hébergement avant de laisser un avis.');
            return $this->redirectToRoute('app_accommodation_showDetails', ['id' => $accommodation->getId()]);
        }

        // Vérifier si un avis a déjà été laissé pour cette réservation
        $existingReview = $reviewRepository->findOneBy(['reservation' => $reservation]);

        if ($existingReview) {
            $this->addFlash('warning', 'Vous avez déjà laissé un avis pour cet hébergement.');
            return $this->redirectToRoute('traveler_reservations');
        }

        // Création de l'avis
        $review = new Review();
        $review->setReservation($reservation);  // Associez l'avis à la réservation
        $review->setDateReview(new \DateTimeImmutable());

        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été ajouté avec succès.');
            return $this->redirectToRoute('app_accommodation_showDetails', ['id' => $accommodation->getId()]);
        }

        return $this->render('review/ReviewFormAccom.html.twig', [
            'form' => $form->createView(),
            'accommodation' => $accommodation,
        ]);
    }



    #[Route('/activity/{id}', name: 'activity')]
    public function ActivityReview(
        Activity $activity,
        ReservationRepository $reservationRepository,
        ReviewRepository $reviewRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $traveler = $this->getUser();

        // Rechercher une réservation pour cette activité et ce voyageur
        $reservation = $reservationRepository->findOneBy([
            'activity' => $activity,
            'traveler' => $traveler,
        ]);

        if (!$reservation) {
            $this->addFlash('error', 'Vous devez réserver cette activité avant de laisser un avis.');
            return $this->redirectToRoute('app_activity_showDetails', ['id' => $activity->getId()]);
        }

        // Vérifier si un avis existe déjà pour cette réservation
        $existingReview = $reviewRepository->findOneBy(['reservation' => $reservation]);

        if ($existingReview) {
            $this->addFlash('warning', 'Vous avez déjà laissé un avis pour cette activité.');
            return $this->redirectToRoute('traveler_reservations');
        }

        // Créer un nouvel avis lié à la réservation
        $review = new Review();
        $review->setReservation($reservation);
        $review->setDateReview(new \DateTimeImmutable());

        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été ajouté avec succès.');
            return $this->redirectToRoute('app_activity_showDetails', ['id' => $activity->getId()]);
        }

        return $this->render('review/ListReviewActivity.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function updateReview(Review $review, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($review->getTraveler()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet avis.');
        }

        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre avis a été mis à jour avec succès.');

            return $this->redirectToRoute('traveler_dashboard'); // Redirection après la mise à jour
        }

        return $this->render('review/UpdateReview.html.twig', [
            'reviewForm' => $form->createView(),
        ]);
    }
    #[Route('delete/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function deleteReview(Review $review, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérification si l'utilisateur est bien l'auteur de l'avis
        if ($review->getTraveler()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet avis.');
        }

        if ($this->isCsrfTokenValid('delete-review' . $review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été supprimé avec succès.');
        }

        return $this->redirectToRoute('traveler_dashboard');
    }
}

