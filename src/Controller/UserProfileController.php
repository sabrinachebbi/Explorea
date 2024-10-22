<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function edit(Request $request, UserProfile $userProfile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');

            return $this->redirectToRoute('userProfile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_profile/edit.html.twig', [
            'userProfile' => $userProfile,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, UserProfile $userProfile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userProfile->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userProfile);
            $entityManager->flush();
        }
        return $this->redirectToRoute('userProfile_show', [], Response::HTTP_SEE_OTHER);
    }
}
