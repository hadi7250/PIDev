<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return array{0: Category[], 1: int, 2: int}
     */
    public function findForDataTable(string $search, int $start, int $length, int $orderCol, string $orderDir): array
    {
        $qb = $this->createQueryBuilder('c')->select('c');
        if ($search !== '') {
            $qb->andWhere('c.name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
        $total = (int) $this->createQueryBuilder('c')->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();
        $qbFiltered = $this->createQueryBuilder('c')->select('COUNT(c.id)');
        if ($search !== '') {
            $qbFiltered->andWhere('c.name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
        $filtered = (int) $qbFiltered->getQuery()->getSingleScalarResult();
        if ($orderCol === 0) {
            $qb->orderBy('c.name', $orderDir === 'desc' ? 'DESC' : 'ASC');
        } else {
            $qb->orderBy('c.id', 'ASC');
        }
        $items = $qb->setFirstResult($start)->setMaxResults($length)->getQuery()->getResult();
        return [$items, $total, $filtered];
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
