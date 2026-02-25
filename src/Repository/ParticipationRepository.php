<?php

namespace App\Repository;

use App\Entity\Participation;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participation>
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    /**
     * @return Participation[]
     */
    public function findByEvent(Event $event, string $order = 'DESC'): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.event = :event')
            ->setParameter('event', $event)
            ->orderBy('p.dateInscription', $order)
            ->getQuery()
            ->getResult();
    }

    public function userHasJoinedEvent(User $user, Event $event): bool
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.user = :user')
            ->andWhere('p.event = :event')
            ->setParameter('user', $user)
            ->setParameter('event', $event)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * @return Participation[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.dateInscription', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array{0: Participation[], 1: int, 2: int}
     */
    public function findForDataTable(string $search, int $start, int $length, int $orderCol, string $orderDir): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.event', 'ev');
        $qb->select('p');
        if ($search !== '') {
            $qb->andWhere('u.email LIKE :search OR ev.titre LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $total = (int) $this->createQueryBuilder('p')->select('COUNT(p.id)')->getQuery()->getSingleScalarResult();
        $qbFiltered = $this->createQueryBuilder('p')->leftJoin('p.user', 'u')->leftJoin('p.event', 'ev')->select('COUNT(p.id)');
        if ($search !== '') {
            $qbFiltered->andWhere('u.email LIKE :search OR ev.titre LIKE :search')->setParameter('search', '%' . $search . '%');
        }
        $filtered = (int) $qbFiltered->getQuery()->getSingleScalarResult();
        $orderFields = ['u.email', 'ev.titre', 'p.dateInscription'];
        if ($orderCol >= 0 && $orderCol < \count($orderFields)) {
            $qb->orderBy($orderFields[$orderCol], $orderDir === 'desc' ? 'DESC' : 'ASC');
        } else {
            $qb->orderBy('p.dateInscription', 'DESC');
        }
        $items = $qb->setFirstResult($start)->setMaxResults($length)->getQuery()->getResult();
        return [$items, $total, $filtered];
    }
}
