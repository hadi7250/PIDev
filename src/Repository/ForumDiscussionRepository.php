<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumDiscussion>
 */
class ForumDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumDiscussion::class);
    }

    /**
     * @return ForumDiscussion[] Returns an array of ForumDiscussion objects
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.messages', 'fm')
            ->groupBy('fd.id')
            ->where('fd.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('fd.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumDiscussion[] Returns an array of recent ForumDiscussion objects
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->orderBy('fd.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumDiscussion[] Returns an array of ForumDiscussion objects by user
     */
    public function findByAuthor(int $authorId): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.category', 'fc')
            ->addSelect('fc')
            ->where('fd.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('fd.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumDiscussion[] Returns an array of ForumDiscussion objects with most messages
     */
    public function findMostActive(int $limit = 10): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.messages', 'fm')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->addSelect('u', 'fc', 'COUNT(fm.id) as messageCount')
            ->groupBy('fd.id')
            ->orderBy('messageCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find most viewed discussion today
     */
    public function findMostViewedToday(): ?ForumDiscussion
    {
        $today = new \DateTimeImmutable('today midnight');
        
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->where('fd.createdAt >= :today')
            ->setParameter('today', $today)
            ->orderBy('fd.views', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find most discussed discussion today
     */
    public function findMostDiscussedToday(): ?ForumDiscussion
    {
        $today = new \DateTimeImmutable('today midnight');
        
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.messages', 'fm')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->where('fd.createdAt >= :today')
            ->groupBy('fd.id')
            ->orderBy('COUNT(fm.id)', 'DESC')
            ->setParameter('today', $today)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find top 5 most viewed discussions overall
     */
    public function findTopViewedOverall(int $limit = 5): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->orderBy('fd.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search discussions by title or content
     * @return ForumDiscussion[]
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('fd')
            ->leftJoin('fd.author', 'u')
            ->leftJoin('fd.category', 'fc')
            ->where('fd.title LIKE :query')
            ->orWhere('fd.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('fd.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ForumDiscussion[] Returns an array of ForumDiscussion objects
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

//    public function findOneBySomeField($value): ?ForumDiscussion
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
