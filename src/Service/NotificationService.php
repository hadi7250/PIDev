<?php

namespace App\Service;

use App\Entity\ForumDiscussion;
use App\Entity\ForumMessage;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationRepository $notificationRepository
    ) {
    }

    /**
     * Create notification when someone replies to a discussion
     */
    public function createReplyToDiscussionNotification(ForumDiscussion $discussion, ForumMessage $reply): void
    {
        $discussionAuthor = $discussion->getAuthor();
        $replyAuthor = $reply->getAuthor();

        // Don't notify if user replies to their own discussion
        if ($discussionAuthor === $replyAuthor) {
            return;
        }

        // Check if notification already exists for this reply
        $existingNotification = $this->notificationRepository->findOneBy([
            'user' => $discussionAuthor,
            'type' => Notification::TYPE_REPLY_TO_DISCUSSION,
            'relatedDiscussion' => $discussion,
            'relatedMessage' => $reply,
        ]);

        if ($existingNotification) {
            return;
        }

        $notification = $this->notificationRepository->createReplyToDiscussionNotification(
            $discussionAuthor,
            $discussion
        );
        $notification->setRelatedMessage($reply);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    /**
     * Create notification when someone replies to a message
     */
    public function createReplyToMessageNotification(ForumMessage $parentMessage, ForumMessage $reply): void
    {
        $messageAuthor = $parentMessage->getAuthor();
        $replyAuthor = $reply->getAuthor();

        // Don't notify if user replies to their own message
        if ($messageAuthor === $replyAuthor) {
            return;
        }

        // Check if notification already exists for this reply
        $existingNotification = $this->notificationRepository->findOneBy([
            'user' => $messageAuthor,
            'type' => Notification::TYPE_REPLY_TO_MESSAGE,
            'relatedDiscussion' => $parentMessage->getDiscussion(),
            'relatedMessage' => $reply,
        ]);

        if ($existingNotification) {
            return;
        }

        $notification = $this->notificationRepository->createReplyToMessageNotification(
            $messageAuthor,
            $parentMessage->getDiscussion(),
            $reply
        );

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
        $this->entityManager->flush();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsReadForUser(User $user): void
    {
        $this->notificationRepository->markAllAsReadForUser($user);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications(User $user): array
    {
        return $this->notificationRepository->findUnreadByUser($user);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return $this->notificationRepository->getUnreadCount($user);
    }

    /**
     * Clean up old read notifications
     */
    public function cleanupOldNotifications(): int
    {
        return $this->notificationRepository->deleteOldReadNotifications();
    }
}
