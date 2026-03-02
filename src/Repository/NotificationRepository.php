<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @return Notification[] Returns an array of Notification objects
     */
    public function findByUser(User $user, bool $unreadOnly = false): array
    {
        $qb = $this->createQueryBuilder('n')
            ->where('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.createdAt', 'DESC');

        if ($unreadOnly) {
            $qb->andWhere('n.isRead = false');
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return Notification[] Returns an array of unread Notification objects
     */
    public function findUnreadByUser(User $user): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return (int) $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsReadForUser(User $user): int
    {
        return $this->createQueryBuilder('n')
            ->update()
            ->set('n.isRead', true)
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    /**
     * Delete old read notifications (older than 30 days)
     */
    public function deleteOldReadNotifications(): int
    {
        $thirtyDaysAgo = new \DateTimeImmutable('-30 days');
        
        return $this->createQueryBuilder('n')
            ->delete()
            ->where('n.isRead = true')
            ->andWhere('n.createdAt < :date')
            ->setParameter('date', $thirtyDaysAgo)
            ->getQuery()
            ->execute();
    }

    /**
     * Create a notification for a reply to discussion
     */
    public function createReplyToDiscussionNotification(User $user, ForumDiscussion $discussion): Notification
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType(Notification::TYPE_REPLY_TO_DISCUSSION);
        $notification->setMessage('Someone replied to your discussion');
        $notification->setRelatedDiscussion($discussion);

        return $notification;
    }

    /**
     * Create a notification for a reply to message
     */
    public function createReplyToMessageNotification(User $user, ForumDiscussion $discussion, ForumMessage $message): Notification
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType(Notification::TYPE_REPLY_TO_MESSAGE);
        $notification->setMessage('Someone replied to your message');
        $notification->setRelatedDiscussion($discussion);
        $notification->setRelatedMessage($message);

        return $notification;
    }
}
