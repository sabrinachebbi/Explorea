<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Picture;
use App\Form\AccommodationFormType;
use App\Repository\AccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/accommodation', name: 'app_accommodation_')]
class AccommodationController extends AbstractController
{
    //fonction pour afficher mes accomodations
    #[Route('/', name: 'showAll')]
    public function index(Request $request ,AccommodationRepository $accommodationRepository): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = $request->query->getInt('limit',6);
        $accommodations = $accommodationRepository->paginate($page, $limit);
        return $this->render('accommodation/accommodation.html.twig', [
            'accommodations' => $accommodations,
        ]);
    }

    //fonction pour ajouter une accommodation
    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_HOST')]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accommodation = new Accommodation();
        $form= $this->createForm(AccommodationFormType::class,$accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accommodation->setHost($this->getUser());
            $accommodation->setCreateDate(new DateTimeImmutable());
            $accommodation ->setUpdateDate(new DateTimeImmutable());
            // Gérer les images
            $pictures = $form->get('pictures')->getData();  // Récupérer les fichiers d'images
            foreach ($pictures as $image) {
                $pictureEntity = new Picture();
                // Associer l'image à l'hébergement
                $pictureEntity->setImageURL('images/');
                $pictureEntity->setAccommodationPictures($accommodation);
                $entityManager->persist($pictureEntity);
            }


            $entityManager->persist($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        return $this->render('accommodation/newAccommodation.html.twig', [
            'AccommodationForm' => $form,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    #[IsGranted('ROLE_USER')]

    public function update(
        Accommodation $accommodation,
        Request $request,
        EntityManagerInterface $entityManager)
      : Response
    {   $user = $this->getUser();
        if (!$user->getAccommodations()->contains($accommodation) && !$this->isGranted('ROLE_ADMIN')){
              return $this->redirectToRoute('app_accommodation_showAll');
        }
        $form = $this->createForm(AccommodationFormType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accommodation->setUpdateDate(new DateTimeImmutable());
            $entityManager->persist($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        return $this->render('accommodation/updateAccommodation.html.twig', [
            'AccommodationForm' => $form,
            'accommodation' => $accommodation,
        ]);
    }
    //fonction pour afficher les details d' une seul annonce
    #[Route('/show/{id}', name: 'showDetails')]
    #[IsGranted('ROLE_USER')]
    public function show( Accommodation $accommodation): Response {

        return $this->render('accommodation/showAccommodation.html.twig',[
            'accommodation' => $accommodation,
        ]);
    }

    //fonction pour supprimer une annonce
    #[Route('/remove/{id}', name: 'remove')]
    #[IsGranted('ROLE_USER')]
    public function remove(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response {
        $user = $this->getUser();
        if (!$user->getAccommodations()->contains($accommodation) && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        $token = $request->getPayload()->get('token');

        if($this->isCsrfTokenValid('delete-acommodation' . $accommodation->getId(), $token)){
            $entityManager->remove($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }

        return $this->redirectToRoute('app_accommodation_showAll');
    }
}

