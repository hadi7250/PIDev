<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/api/notifications', name: 'api_notifications')]
    public function getNotifications(NotificationRepository $repo): JsonResponse
    {
        $notifications = $repo->findBy([], ['createdAt' => 'DESC'], 5);
        $data = [];
        foreach ($notifications as $n) {
            $data[] = [
                'message' => $n->getMessage(),
                'link'    => $n->getLink(),
                'date'    => $n->getCreatedAt()->format('d M H:i')
            ];
        }
        return $this->json($data);
    }
}