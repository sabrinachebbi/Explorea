<?php

namespace App\Controller\Administration;

use App\Entity\Accommodation;
use App\Form\administration\AccommodationType;
use App\Repository\AccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;

#[Route('/admin/accommodation',name: 'app_admin_accommodation_')]
class AdminAccommodationsController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(AccommodationRepository $accommodationRepository): Response
    {
        return $this->render('administration/admin_accommodations/index.html.twig', [
            'accommodations' => $accommodationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accommodation = new Accommodation();
        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accommodation->setCreateDate(new DateTimeImmutable());
            $accommodation ->setUpdateDate(new DateTimeImmutable());
            $entityManager->persist($accommodation);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_accommodation_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_accommodations/new.html.twig', [
            'accommodation' => $accommodation,
            'form' => $form,
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

            return $this->redirectToRoute('app_admin_accommodation_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_accommodations/edit.html.twig', [
            'accommodation' => $accommodation,
            'form1' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accommodation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accommodation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_accommodation_list', [], Response::HTTP_SEE_OTHER);
    }
}
