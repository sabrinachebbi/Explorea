<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Enum\UserStatus;
use App\Form\UserProfileType;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/userProfile',name: 'userProfile_')]
class UserProfileController extends AbstractController
{
    #[Route('/', name: 'show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();

        return $this->render('host_dashboard/dashboard.html.twig', [
            'userProfile' => $userProfile,
            'section' => 'profile',
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        UserProfile $userProfile,
        EntityManagerInterface $entityManager,
        NotificationRepository $notificationRepository
    ): Response {
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');

            return $this->redirectToRoute('userProfile_show', [], Response::HTTP_SEE_OTHER);
        }

        // Récupérer le nombre de notifications non lues
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $unreadNotifications = $notificationRepository->count(['user' => $user, 'isRead' => false]);

        return $this->render('user_profile/edit.html.twig', [
            'userProfile' => $userProfile,
            'form' => $form,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        if ($user) {
            $user->setStatusUser(UserStatus::ARCHIVED);
            $entityManager->flush();

            // Invalider la session et déconnecter l'utilisateur
            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

        }

        return $this->redirectToRoute('app_login');
    }


}
