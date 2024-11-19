<?php

namespace App\Controller\Administration;

use App\Entity\Notification;
use App\Form\administration\NotificationType;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/notification', name: 'app_admin_notification_')]
class AdminNotificationController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(NotificationRepository $notificationRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $notificationRepository->findAll();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin_dashboard/Dashboard.html.twig', [
            'pagination' => $pagination,
            'section' => 'notifications',
        ]);
    }


    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_notification_list', [], Response::HTTP_SEE_OTHER);
    }
}
