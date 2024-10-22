<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Enum\GenderEnum;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher , EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedRole = $form->get('role')->getData();
            $user->setRoles([$selectedRole]);
            $user->setPassword(
                $userPasswordHasher-> hashPassword  (
                    $user,
                    $form->get('password')->getData()
                )
            );
            // Créer un nouveau profil utilisateur
            $userProfile = new UserProfile();
            $userProfile->setUser($user); // Lie le profil à l'utilisateur
            $user->setUserProfile($userProfile);
            $userProfile->setFirstName($form->get('firstName')->getData());
            $userProfile->setLastName($form->get('lastName')->getData());
            $userProfile->setGender($form->get('gender')->getData());





            $entityManager->persist($user);
            $entityManager->persist($userProfile);
            $entityManager->flush();
            $this->addFlash('success',message: 'Bienvenue parmi nous, nouveau utilisateur !');
            return $this->redirectToRoute('app_login');
        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);

    }

}
