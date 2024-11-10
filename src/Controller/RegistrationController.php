<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un token de vérification
            $user->setVerificationToken(Uuid::v4());

            // Gérer le rôle et le mot de passe de l'utilisateur
            $selectedRole = $form->get('role')->getData();
            $user->setRoles([$selectedRole]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Créer un profil utilisateur
            $userProfile = new UserProfile();
            $userProfile->setUser($user);
            $userProfile->setFirstName($form->get('firstName')->getData());
            $userProfile->setLastName($form->get('lastName')->getData());
            $userProfile->setGender($form->get('gender')->getData());

            $entityManager->persist($user);
            $entityManager->persist($userProfile);
            $entityManager->flush();

            // Envoi de l'email de vérification immédiatement après l'inscription
            $this->envoyerEmailDeVerification($user, $mailer);

            $this->addFlash('success', 'Bienvenue parmi nous ! Veuillez vérifier votre adresse email pour activer votre compte.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify-email/{token}', name: 'verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['verificationToken' => $token]);

        // Vérifie si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Token de vérification invalide ou expiré.');
            return $this->redirectToRoute('app_login');
        }

        // Marquer le compte comme vérifié en supprimant le token
        $user->setVerificationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été vérifié avec succès !');

        return $this->redirectToRoute('app_login');
    }

    private function envoyerEmailDeVerification(User $user, MailerInterface $mailer): void
    {
        $verificationUrl = $this->generateUrl('verify_email', [
            'token' => $user->getVerificationToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $contenu = "
        <p>Bonjour,</p>
        <p>Merci de vous être inscrit ! Veuillez cliquer sur le lien suivant pour vérifier votre compte :</p>
        <p><a href=\"$verificationUrl\">Vérifier mon compte</a></p>
        <p>Merci, l'équipe Exploréa !</p>
    ";

        $email = (new Email())
            ->from('no-reply@explorea.com')
            ->to($user->getEmail())
            ->subject('Vérification de votre compte')
            ->html($contenu);

        $mailer->send($email);
    }
}
