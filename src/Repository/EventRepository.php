<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return array{0: Event[], 1: int, 2: int}
     */
    public function findForDataTable(string $search, int $start, int $length, int $orderCol, string $orderDir): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'c');
        $qb->select('e');
        if ($search !== '') {
            $qb->andWhere('e.titre LIKE :search OR e.lieu LIKE :search OR c.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $total = (int) $this->createQueryBuilder('e')->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $qbFiltered = $this->createQueryBuilder('e')->leftJoin('e.category', 'c')->select('COUNT(e.id)');
        if ($search !== '') {
            $qbFiltered->andWhere('e.titre LIKE :search OR e.lieu LIKE :search OR c.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $filtered = (int) $qbFiltered->getQuery()->getSingleScalarResult();
        $orderFields = ['e.titre', 'c.name', 'e.dateDebut', 'e.dateFin', 'e.lieu', 'e.duree', 'e.nombreMaxParticipants', 'e.image'];
        if ($orderCol >= 0 && $orderCol < \count($orderFields)) {
            $qb->orderBy($orderFields[$orderCol], $orderDir === 'desc' ? 'DESC' : 'ASC');
        } else {
            $qb->orderBy('e.dateDebut', 'DESC');
        }
        $items = $qb->setFirstResult($start)->setMaxResults($length)->getQuery()->getResult();
        return [$items, $total, $filtered];
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
