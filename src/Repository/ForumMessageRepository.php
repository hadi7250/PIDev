<?php

namespace App\Repository;

use App\Entity\ForumMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumMessage>
 */
class ForumMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumMessage::class);
    }

    /**
     * @return ForumMessage[] Returns an array of ForumMessage objects for a discussion
     */
    public function findByDiscussion(int $discussionId): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->where('fm.discussion = :discussionId')
            ->setParameter('discussionId', $discussionId)
            ->orderBy('fm.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumMessage[] Returns an array of ForumMessage objects by user
     */
    public function findByAuthor(int $authorId, int $limit = 50): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.discussion', 'fd')
            ->leftJoin('fm.author', 'u')
            ->addSelect('fd', 'u')
            ->where('fm.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('fm.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumMessage[] Returns an array of most upvoted ForumMessage objects
     */
    public function findMostUpvoted(int $limit = 10): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->leftJoin('fm.discussion', 'fd')
            ->addSelect('u', 'fd')
            ->orderBy('fm.upvotes', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumMessage[] Returns an array of highest scored ForumMessage objects
     */
    public function findHighestScored(int $limit = 10): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->leftJoin('fm.discussion', 'fd')
            ->addSelect('u', 'fd')
            ->orderBy('(fm.upvotes - fm.downvotes)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search messages by content
     * @return ForumMessage[]
     */
    public function search(string $query, int $limit = 50): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->leftJoin('fm.discussion', 'fd')
            ->addSelect('u', 'fd')
            ->where('fm.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('fm.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get message statistics for a discussion
     */
    public function getDiscussionStats(int $discussionId): array
    {
        $result = $this->createQueryBuilder('fm')
            ->select('COUNT(fm.id) as totalMessages', 
                    'SUM(fm.upvotes) as totalUpvotes', 
                    'SUM(fm.downvotes) as totalDownvotes')
            ->where('fm.discussion = :discussionId')
            ->setParameter('discussionId', $discussionId)
            ->getQuery()
            ->getSingleResult();

        return [
            'totalMessages' => (int) $result['totalMessages'],
            'totalUpvotes' => (int) $result['totalUpvotes'],
            'totalDownvotes' => (int) $result['totalDownvotes'],
        ];
    }

    /**
     * Find root messages (messages without parent) for a discussion
     */
    public function findRootMessages(int $discussionId): array
    {
        return $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->leftJoin('fm.replies', 'replies')
            ->where('fm.discussion = :discussionId')
            ->andWhere('fm.parent IS NULL')
            ->setParameter('discussionId', $discussionId)
            ->orderBy('fm.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all messages for a discussion organized in hierarchical structure
     */
    public function findMessagesHierarchy(int $discussionId): array
    {
        // Get all messages for the discussion
        $allMessages = $this->createQueryBuilder('fm')
            ->leftJoin('fm.author', 'u')
            ->leftJoin('fm.parent', 'parent')
            ->where('fm.discussion = :discussionId')
            ->setParameter('discussionId', $discussionId)
            ->orderBy('fm.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        // Organize into hierarchy
        $rootMessages = [];
        $messagesById = [];

        // Index messages by ID
        foreach ($allMessages as $message) {
            $messagesById[$message->getId()] = $message;
        }

        // Build hierarchy
        foreach ($allMessages as $message) {
            if ($message->getParent() === null) {
                $rootMessages[] = $message;
            } else {
                $parentId = $message->getParent()->getId();
                if (isset($messagesById[$parentId])) {
                    $messagesById[$parentId]->addReply($message);
                }
            }
        }

        return $rootMessages;
    }

    /**
     * Check if a message can be replied to (prevent deep nesting)
     */
    public function canReplyToMessage(int $messageId, int $maxDepth = 3): bool
    {
        $message = $this->find($messageId);
        if (!$message) {
            return false;
        }

        $depth = 0;
        $current = $message;
        
        while ($current->getParent() !== null && $depth < $maxDepth) {
            $current = $current->getParent();
            $depth++;
        }

        return $depth < $maxDepth;
    }

//    /**
//     * @return ForumMessage[] Returns an array of ForumMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ForumMessage
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
