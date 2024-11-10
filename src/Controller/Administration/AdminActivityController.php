<?php

namespace App\Controller\Administration;

use App\Entity\Activity;
use App\Form\ActivityFormType;
use App\Form\administration\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;

#[Route('/admin/activity',name: 'app_admin_activity_')]
class AdminActivityController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(ActivityRepository $activitiesRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $activitiesRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'pagination' => $pagination,
            'section' => 'activities',
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityFormType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activity->setCreateDate(new DateTimeImmutable());
            $activity ->setUpdateDate(new DateTimeImmutable());
            $entityManager->persist($activity);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_activity_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/newActivity.html.twig', [
            'activity' => $activity,
            'ActivityForm' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Activity $activity): Response
    {
        return $this->render('administration/admin_activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivityFormType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activity ->setUpdateDate(new DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_activity_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/updateActivities.html.twig', [
            'activity' => $activity,
            'ActivityForm' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_activity_list', [], Response::HTTP_SEE_OTHER);
    }
}
