<?php
namespace App\Controller\Administration;
use App\Entity\Reservation;
use App\Form\administration\AdminReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservation', name: 'admin_reservation_')]
class AdminReservationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ReservationRepository $reservationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer toutes les réservations
        $queryBuilder = $reservationRepository->findAll();

        // Pagination
        $pagination = $paginator->paginate(
            $queryBuilder, // Requête
            $request->query->getInt('page', 1),
            5
        );


        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'pagination' => $pagination,
            'section' => 'reservations',
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('administration/admin_reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Ajouter un message flash pour la mise à jour
            $this->addFlash('success', 'La réservation a été mise à jour avec succès.');

            return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le token CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            // Ajouter un message flash pour la suppression
            $this->addFlash('success', 'La réservation a été supprimée avec succès.');

        }

        return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
