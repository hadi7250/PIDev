<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/api/notifications', name: 'api_notifications')]
    public function getNotifications(NotificationRepository $repo): JsonResponse
    {
        // 1. Get the 5 most recent notifications (history)
        $notifications = $repo->findBy([], ['createdAt' => 'DESC'], 5);
        
        // 2. Count ONLY the unread ones for the red badge
        $unreadCount = $repo->count(['isRead' => false]);

        $data = [];
        foreach ($notifications as $n) {
            $data[] = [
                'id'      => $n->getId(),
                'message' => $n->getMessage(),
                'link'    => $n->getLink(),
                'date'    => $n->getCreatedAt()->format('d M H:i'),
                'isRead'  => $n->isRead()
            ];
        }

        // Return both the list and the unread count
        return $this->json([
            'count' => $unreadCount,
            'notifications' => $data
        ]);
    }

    // ⬇️ NEW ENDPOINT: Marks all notifications as read
    #[Route('/api/notifications/read-all', name: 'api_notifications_read_all', methods: ['POST'])]
    public function readAllNotifications(NotificationRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        // Find all unread notifications
        $unreadNotifications = $repo->findBy(['isRead' => false]);

        foreach ($unreadNotifications as $notification) {
            $notification->setIsRead(true);
        }

        $em->flush();

        return $this->json(['status' => 'success']);
    }
}