<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * @return Rating[]
     */
    public function findByEvent($event, string $order = 'DESC'): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.event = :event')
            ->setParameter('event', $event)
            ->orderBy('r.dateCreation', $order)
            ->getQuery()
            ->getResult();
    }

    public function userHasRatedEvent($user, $event): bool
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.user = :user')
            ->andWhere('r.event = :event')
            ->setParameter('user', $user)
            ->setParameter('event', $event)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * @return array{0: Rating[], 1: int, 2: int}
     */
    public function findForDataTable(string $search, int $start, int $length, int $orderCol, string $orderDir): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.event', 'ev');
        $qb->select('r');
        if ($search !== '') {
            $qb->andWhere('u.email LIKE :search OR ev.titre LIKE :search OR r.commentaire LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $total = (int) $this->createQueryBuilder('r')->select('COUNT(r.id)')->getQuery()->getSingleScalarResult();
        $qbFiltered = $this->createQueryBuilder('r')->leftJoin('r.user', 'u')->leftJoin('r.event', 'ev')->select('COUNT(r.id)');
        if ($search !== '') {
            $qbFiltered->andWhere('u.email LIKE :search OR ev.titre LIKE :search OR r.commentaire LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $filtered = (int) $qbFiltered->getQuery()->getSingleScalarResult();
        $orderFields = ['u.email', 'ev.titre', 'r.note', 'r.commentaire', 'r.dateCreation'];
        if ($orderCol >= 0 && $orderCol < \count($orderFields)) {
            $qb->orderBy($orderFields[$orderCol], $orderDir === 'desc' ? 'DESC' : 'ASC');
        } else {
            $qb->orderBy('r.dateCreation', 'DESC');
        }
        $items = $qb->setFirstResult($start)->setMaxResults($length)->getQuery()->getResult();
        return [$items, $total, $filtered];
    }
}
