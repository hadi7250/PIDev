<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Discussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findByDiscussion(Discussion $discussion): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.discussion = :discussion')
            ->setParameter('discussion', $discussion)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByAuthor(string $authorName): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.authorName = :authorName')
            ->setParameter('authorName', $authorName)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
