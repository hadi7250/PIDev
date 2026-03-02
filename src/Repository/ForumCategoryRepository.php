<?php

namespace App\Repository;

use App\Entity\ForumCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumCategory>
 */
class ForumCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumCategory::class);
    }

    /**
     * @return ForumCategory[] Returns an array of ForumCategory objects
     */
    public function findAllWithDiscussionCount(): array
    {
        return $this->createQueryBuilder('fc')
            ->leftJoin('fc.discussions', 'fd')
            ->groupBy('fc.id')
            ->orderBy('fc.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumCategory[] Returns an array of ForumCategory objects ordered by most discussions
     */
    public function findByMostDiscussions(int $limit = 10): array
    {
        return $this->createQueryBuilder('fc')
            ->leftJoin('fc.discussions', 'fd')
            ->groupBy('fc.id')
            ->orderBy('COUNT(fd.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ForumCategory[] Returns an array of ForumCategory objects
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

//    public function findOneBySomeField($value): ?ForumCategory
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
