<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favorites',name: 'toggle-favorite_')]
class FavoriteController extends AbstractController
{
    #[Route('/accommodation/{id}/toggle', name: 'accommodation')]
    #[IsGranted('ROLE_TRAVELER')]
    public function toggleAccommodationFavorite(Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getFavoriteAccommodation()->contains($accommodation)) {
            $user->removeFavoriteAccommodation($accommodation); // Retirer des favoris
            $this->addFlash('warning', 'Vous avez retiré cette annonce de vos favoris.');
        } else {
            $user->addFavoriteAccommodation($accommodation); // Ajouter aux favoris
            $this->addFlash('success', 'Vous avez ajouté cette annonce à vos favoris.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_accommodation_showAll'); // Redirection après modification
    }

    #[Route('/activity/{id}/toggle', name: 'activity')]
    #[IsGranted('ROLE_TRAVELER')]
    public function toggleActivityFavorite(Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getFavoriteActivities()->contains($activity)) {
            $user->removeFavoriteActivity($activity);
            $this->addFlash('warning', 'Vous avez retiré cette annonce de vos favoris.');
        } else {
            $user->addFavoriteActivity($activity);
            $this->addFlash('success', 'Vous avez ajouté cette annonce à vos favoris.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_activity_showAll');
    }

    #[Route('/List', name: 'List')]
    public function listFavorites(EntityManagerInterface $em ,NotificationRepository $notificationRepository): Response
    {
         // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les hébergements et les activités favoris de l'utilisateur
        $favoriteAccommodations = $user->getFavoriteAccommodation();
        $favoriteActivities = $user->getFavoriteActivities();
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;


        return $this->render('favorite/favorite.html.twig', [
            'accommodations' => $favoriteAccommodations,
            'activities' => $favoriteActivities,
            'unreadNotifications' => $unreadNotifications
        ]);

    }
}

