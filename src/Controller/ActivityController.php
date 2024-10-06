<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Form\ActivityFormType;
use App\Repository\ActivitiesRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;


#[Route('/activity', name: 'app_activity_')]
class ActivityController extends AbstractController
{

    //fonction pour afficher mes activities
    #[Route('/', name: 'showAll')]
    public function index(Request $request ,ActivitiesRepository $activityRepository): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = $request->query->getInt('limit',6);
        $activities = $activityRepository->paginate($page, $limit);

        return $this->render('activity/activity.html.twig', [
            'activities' => $activities,
        ]);
    }

    //fonction pour ajouter une activity
    #[Route('/new', name: 'new')]
    public function new(
        Request $request, EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository): Response
    {
        $category= $categoryRepository->findAll();
        $activities= new Activity();
        $form= $this->createForm(ActivityFormType::class,$activities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activities->setCreateDate(new DateTimeImmutable());
            $activities ->setUpdateDate(new DatetimeImmutable());

            $entityManager->persist($activities);
            $entityManager->flush();
            return $this->redirectToRoute('app_activity_showAll');
        }
        return $this->render('activity/newActivity.html.twig', [
            'ActivityForm' => $form,
        ]);

    }
    //fonction pour afficher les details d' une seul annonce d'activity
    #[Route('/show/{id}', name: 'showDetails')]
    public function show( Activity $activities): Response {

        return $this->render('activity/ShowActivity.html.twig',[
            'activity' => $activities,
        ]);
    }

//fonction pour supprimer une annonce
    #[Route('/remove/{id}', name: 'remove')]
    public function remove( Activity $activities, EntityManagerInterface $entityManager): Response {

        $entityManager->remove($activities);
        $entityManager->flush();

        return $this->redirectToRoute('app_activity_showAll');
    }

}

