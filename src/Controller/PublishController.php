<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Activity;
use App\Form\AccommodationFormType;
use App\Form\ActivityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    #[Route('/publish', name: 'app_publish')]
    public function publish(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // crée la formulaire pour accommodation
        $accommodation = new Accommodation();
        $form1 = $this->createForm(AccommodationFormType::class, $accommodation);
        // crée la formulaire pour activity
        $activity = new Activity();
        $form = $this->createForm(ActivityFormType::class, $activity);


        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $accommodation->setHost($this->getUser());
            $accommodation->setCreateDate(new \DateTimeImmutable());
            $accommodation ->setUpdateDate(new \DateTimeImmutable());
            $entityManager->persist($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');  //rediriger vers la page d'affichage aprés l'ajout
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $activity->setHost($this->getUser());
            $activity->setCreateDate(new \DateTimeImmutable());
            $activity ->setUpdateDate(new \DateTimeImmutable());
            $entityManager->persist($activity);
            $entityManager->flush();
            return $this->redirectToRoute('app_activity_showAll');
        }

        return $this->render('_partials/publish-ad.html.twig', [
            'AccommodationForm' => $form1,
            'ActivityForm' => $form,
        ]);
    }
}
