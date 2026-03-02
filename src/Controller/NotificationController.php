<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
class NotificationController extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }

    /**
     * Get unread notifications count (AJAX endpoint)
     */
    #[Route('/unread-count', name: 'notifications_unread_count', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUnreadCount(): JsonResponse
    {
        $user = $this->getUser();
        $count = $this->notificationService->getUnreadCount($user);

        return new JsonResponse([
            'count' => $count,
        ]);
    }

    /**
     * Get recent notifications (AJAX endpoint)
     */
    #[Route('/recent', name: 'notifications_recent', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getRecentNotifications(): JsonResponse
    {
        $user = $this->getUser();
        $notifications = $this->notificationService->getUnreadNotifications($user);

        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->getId(),
                'type' => $notification->getType(),
                'message' => $notification->getFormattedMessage(),
                'actionUrl' => $notification->getActionUrl(),
                'createdAt' => $notification->getCreatedAt()->format('M d, Y H:i'),
            ];
        }

        return new JsonResponse([
            'notifications' => $data,
        ]);
    }

    /**
     * Mark notification as read
     */
    #[Route('/{id}/mark-read', name: 'notification_mark_read', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function markAsRead(
        Notification $notification,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Check if notification belongs to current user
        if ($notification->getUser() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Unauthorized'], 403);
        }

        $this->notificationService->markAsRead($notification);

        return new JsonResponse([
            'success' => true,
            'unreadCount' => $this->notificationService->getUnreadCount($this->getUser()),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    #[Route('/mark-all-read', name: 'notifications_mark_all_read', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function markAllAsRead(): JsonResponse
    {
        $user = $this->getUser();
        $this->notificationService->markAllAsReadForUser($user);

        return new JsonResponse([
            'success' => true,
            'unreadCount' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    #[Route('/{id}/delete', name: 'notification_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteNotification(
        Notification $notification,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Check if notification belongs to current user
        if ($notification->getUser() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Unauthorized'], 403);
        }

        $entityManager->remove($notification);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'unreadCount' => $this->notificationService->getUnreadCount($this->getUser()),
        ]);
    }
}
