<?php

namespace App\Controller\Administration;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/review')]
class AdminReviewController extends AbstractController
{
    #[Route('/', name: 'app_admin_review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $reviewRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'pagination' => $pagination,
            'section' => 'reviews',

        ]);
    }

    #[Route('/new', name: 'app_admin_review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_review/new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_review_show', methods: ['GET'])]
    public function show(Review $review): Response
    {
        return $this->render('admin_review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/admin_review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
