<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Form\ActivityFilterType;
use App\Form\ActivityFormType;
use App\Repository\ActivityRepository;
use App\Repository\CategoryRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/activity', name: 'app_activity_')]
class ActivityController extends AbstractController
{

    //fonction pour afficher mes activities
    #[Route('/', name: 'showAll')]
    public function index(Request $request , ActivityRepository $activityRepository,PaginatorInterface $paginator,NotificationRepository $notificationRepository): Response
    {
        // Créer le formulaire de filtrage
        $form = $this->createForm(ActivityFilterType::class);
        $form->handleRequest($request);

        $page = $request->query->getInt('page', 1);
        $limit = 6;

        // Si le formulaire est soumis et valide, appliquer le filtrage
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $activityRepository->filterActivity(
                $form->get('category')->getData(),
                $form->get('duration')->getData(),
                $form->get('country')->getData(),
                $form->get('priceMin')->getData(),
                $form->get('priceMax')->getData()
            );
        } else {
            $query = $activityRepository->createQueryBuilder('ac')->getQuery();
        }
        $activities= $paginator->paginate(
            $query,
            $page, // Numéro de la page
            $limit // Nombre d'éléments par page
        );
        $user = $this->getUser();
        $unreadNotifications = $user ? $notificationRepository->count(['user' => $user, 'isRead' => false]) : 0;


        return $this->render('activity/activity.html.twig', [
            'form' => $form->createView(),
            'activities' => $activities,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    //fonction pour ajouter une activity
    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_HOST')]
    public function new(
        Request $request, EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository): Response
    {
        $category= $categoryRepository->findAll();
        $activities= new Activity();
        $form= $this->createForm(ActivityFormType::class,$activities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activities->setHost($this->getUser());
            $activities->setCreateDate(new DateTimeImmutable());
            $activities ->setUpdateDate(new DatetimeImmutable());
            $activities->setDuration($request->request->get('duration'));
            if ($activities->getPicture()) {
                $activities->getPicture()->setActivity($activities);
                $entityManager->persist($activities->getPicture());
            }

            $entityManager->persist($activities);

            $entityManager->flush();
            return $this->redirectToRoute('app_activity_showAll');
        }
        return $this->render('activity/newActivity.html.twig', [
            'ActivityForm' => $form,
        ]);

    }
#[Route('/update/{id}', name: 'update')]
    #[IsGranted('ROLE_HOST')]

    public function update(
        Activity $activities,
        Request $request,
        EntityManagerInterface $entityManager)
    : Response
    {   $user = $this->getUser();
        if (!$user->getActivities()->contains($activities) && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_activity_showAll');
        }
        $form = $this->createForm(ActivityFormType::class, $activities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activities->setUpdateDate(new DateTimeImmutable());
            $entityManager->persist($activities);
            $entityManager->flush();
            return $this->redirectToRoute('app_activity_showDetails', ['id' => $activities->getId()]);
        }
        return $this->render('activity/updateActivities.html.twig', [
            'ActivityForm' => $form,
            'activity' => $activities,
        ]);
    }
    //fonction pour afficher les details d' une seul annonce d'activity
    #[Route('/show/{id}', name: 'showDetails')]
    public function show( Activity $activity): Response {

        return $this->render('activity/ShowActivity.html.twig',[
            'activity' => $activity,
        ]);
    }

//fonction pour supprimer une annonce
    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(int $id, EntityManagerInterface $entityManager): Response
    {
        // Recherche de l'activité à supprimer
        $activity = $entityManager->getRepository(Activity::class)->find($id);

        // Suppression de l'activité
        $entityManager->remove($activity);
        $entityManager->flush();

        // Redirection après suppression
        return $this->redirectToRoute('app_activity_showAll');
    }



}

