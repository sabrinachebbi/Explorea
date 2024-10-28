<?php

namespace App\Controller\Administration;

use App\Entity\ReservationStatus;
use App\Form\administration\ReservationStatusType;
use App\Repository\ReservationStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservation-status', name: 'app_admin_reservation_status_')]
class AdminResvStatusController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(ReservationStatusRepository $reservationStatusRepository): Response
    {

        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'statusList' => $reservationStatusRepository->findAll(),
            'section' => 'status',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $status = new ReservationStatus();
        $form = $this->createForm(ReservationStatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($status);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reservation_status_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_reservation_status/newStatus.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationStatus $status, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationStatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reservation_status_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_reservation_status/edit.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, ReservationStatus $status, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$status->getId(), $request->get('_token'))) {
            $entityManager->remove($status);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_reservation_status_list', [], Response::HTTP_SEE_OTHER);
    }
}
