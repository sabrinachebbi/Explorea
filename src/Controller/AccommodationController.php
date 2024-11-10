<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Picture;
use App\Entity\Reservation;
use App\Form\AccommodationFilterType;
use App\Form\AccommodationFormType;
use App\Form\ReservationAccommodationFormType;
use App\Repository\AccommodationRepository;
use App\Repository\NotificationRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/accommodation', name: 'app_accommodation_')]
class AccommodationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function index(Request $request, AccommodationRepository $accommodationRepository, PaginatorInterface $paginator,NotificationRepository $notificationRepository): Response
    {
        // Créer le formulaire de filtrage
        $form = $this->createForm(AccommodationFilterType::class);
        $form->handleRequest($request);

        $page = $request->query->getInt('page', 1);
        $limit = 6;

        // Si le formulaire est soumis et valide, appliquer le filtrage
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $accommodationRepository->filterAccommodation(
                $form->get('propertyType')->getData(),
                $form->get('country')->getData(),
                $form->get('priceMin')->getData(),
                $form->get('priceMax')->getData()
            );
        } else {
            // Par défaut, on récupère toutes les accommodations
            $query = $accommodationRepository->createQueryBuilder('a')->getQuery();
        }

        // Pagination des résultats
        $accommodations = $paginator->paginate(
            $query,
            $page, // Numéro de la page
            $limit // Nombre d'éléments par page
        );
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;

        return $this->render('accommodation/accommodation.html.twig', [
            'form' => $form->createView(),
            'accommodations' => $accommodations,
            'unreadNotifications' => $unreadNotifications
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
            foreach ($accommodation->getPictures() as $picture) {
                $picture->setAccommodation($accommodation);
                $entityManager->persist($picture);
            }

            $entityManager->persist($accommodation);



            $entityManager->persist($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        return $this->render('accommodation/newAccommodation.html.twig', [
            'AccommodationForm' => $form,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    #[IsGranted('ROLE_HOST')]

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
    public function show( Accommodation $accommodation, ReviewRepository $reviewRepository): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationAccommodationFormType::class, $reservation, [
            'action' => $this->generateUrl('reservation_accommodation', ['id' => $accommodation->getId()]),
            'method' => 'POST'
        ]);
        // Récupérer les avis associés aux réservations de l'hébergement
        $reviews = $reviewRepository->findByAccommodation($accommodation);

        return $this->render('accommodation/showAccommodation.html.twig',[
            'accommodation' => $accommodation,
            'reservationForm' => $form->createView(),  // Passer le formulaire au template
            'reviews' => $reviews,  // Passez les avis au template
        ]);
    }

    //fonction pour supprimer une annonce
    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    #[IsGranted('ROLE_TRAVELER')]
    public function remove(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response {
        $user = $this->getUser();
        if (!$user->getAccommodations()->contains($accommodation) && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_accommodation_showAll');
        }
        $token = $request->request->get('_token');

        if($this->isCsrfTokenValid('delete-acommodation' . $accommodation->getId(), $token)){

            //supprimer les images associées aussi
            foreach ($accommodation->getPictures() as $picture) {
                $entityManager->remove($picture);
            }
            $entityManager->remove($accommodation);
            $entityManager->flush();
            return $this->redirectToRoute('app_accommodation_showAll');
        }

        return $this->redirectToRoute('app_accommodation_showAll');
    }
}

