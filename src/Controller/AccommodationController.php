<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Form\AccomodationFormType;
use App\Repository\AccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;




#[Route('/accommodation', name: 'app_accommodation_')]
class AccommodationController extends AbstractController
{
    //fonction pour afficher mes accomodations
    #[Route('/', name: 'showAll')]
    public function index(AccommodationRepository $accommodationRepository): Response
    {
        $accommodations = $accommodationRepository->findAll();
        return $this->render('accommodation/accommodation.html.twig', [
            'accommodations' => $accommodations,
        ]);
    }

    //fonction pour ajouter une accommodation
    #[Route('/new', name: 'new')]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accommodation = new Accommodation();
        $form= $this->createForm(AccomodationFormType::class,$accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accommodation->setCreateDate(new DateTimeImmutable());
            $accommodation ->setUpdateDate(new DateTimeImmutable());

            $entityManager->persist($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        return $this->render('accommodation/newAccommodation.html.twig', [
            'AccommodationForm' => $form,
        ]);

    }
    //fonction pour afficher les details d' une seul annonce
    #[Route('/show/{id}', name: 'showDetails')]
    public function show( Accommodation $accommodation): Response {

        return $this->render('accommodation/showAccommodation.html.twig',[
            'accommodation' => $accommodation,
        ]);
    }

    //fonction pour supprimer une annonce
    #[Route('/remove/{id}', name: 'remove')]
    public function remove( Accommodation $accommodation, EntityManagerInterface $entityManager): Response {

        $entityManager->remove($accommodation);
        $entityManager->flush();

        return $this->redirectToRoute('app_accommodation_showAll');
    }

}

