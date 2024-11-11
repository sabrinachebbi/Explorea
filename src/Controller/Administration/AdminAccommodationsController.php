<?php

namespace App\Controller\Administration;

use App\Entity\Accommodation;
use App\Form\AccommodationFormType;
use App\Form\administration\AccommodationType;
use App\Repository\AccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;

#[Route('/admin/accommodation',name: 'app_admin_accommodation_')]
class AdminAccommodationsController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(AccommodationRepository $accommodationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $accommodationRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
           5
        );
        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'pagination' => $pagination,
            'section' => 'accommodations',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accommodation = new Accommodation();
        $form = $this->createForm(AccommodationFormType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $host = $this->getUser(); // ou récupérer un hôte spécifique
            $accommodation->setHost($host);
            $accommodation->setCreateDate(new DateTimeImmutable());
            $accommodation ->setUpdateDate(new DateTimeImmutable());
            foreach ($accommodation->getPictures() as $picture) {
                $picture->setAccommodation($accommodation);
                $entityManager->persist($picture);
            }

            $entityManager->persist($accommodation);
            $entityManager->flush();
            // Ajouter un message flash pour la création réussie
            $this->addFlash('success', 'L\'hébergement a été créé avec succès.');


            return $this->redirectToRoute('app_admin_accommodation_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accommodation/newAccommodation.html.twig', [
            'accommodation' => $accommodation,
            'AccommodationForm' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Accommodation $accommodation): Response
    {
        return $this->render('administration/admin_accommodations/show.html.twig', [
            'accommodation' => $accommodation,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accommodation->setUpdateDate(new DateTimeImmutable());
            $entityManager->flush();
            foreach ($accommodation->getPictures() as $picture) {
                $picture->setAccommodation($accommodation);
                $entityManager->persist($picture);
            }
            // Ajouter un message flash pour la modification réussie
            $this->addFlash('success', 'L\'hébergement a été mis à jour avec succès.');

            return $this->redirectToRoute('app_admin_accommodation_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accommodation/updateAccommodation.html.twig', [
            'accommodation' => $accommodation,
            'AccommodationForm' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accommodation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accommodation);
            $entityManager->flush();
            // Ajouter un message flash pour la suppression réussie
            $this->addFlash('success', 'L\'hébergement a été supprimé avec succès.');
        } else {
            // Ajouter un message flash pour un échec de la suppression
            $this->addFlash('error', 'Échec de la suppression de l\'hébergement.');
        }


        return $this->redirectToRoute('app_admin_accommodation_list');
    }
}
