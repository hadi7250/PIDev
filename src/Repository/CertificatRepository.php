<?php

namespace App\Repository;

use App\Entity\Certificat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Certificat>
 */
class CertificatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certificat::class);
    }

    /**
     * @return array{0: Certificat[], 1: int, 2: int}
     */
    public function findForDataTable(string $search, int $start, int $length, int $orderCol, string $orderDir): array
    {
        $qb = $this->createQueryBuilder('cert')
            ->leftJoin('cert.user', 'u')
            ->leftJoin('cert.event', 'ev');
        $qb->select('cert');
        if ($search !== '') {
            $qb->andWhere('u.email LIKE :search OR ev.titre LIKE :search OR cert.codeUnique LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $total = (int) $this->createQueryBuilder('cert')->select('COUNT(cert.id)')->getQuery()->getSingleScalarResult();
        $qbFiltered = $this->createQueryBuilder('cert')->leftJoin('cert.user', 'u')->leftJoin('cert.event', 'ev')->select('COUNT(cert.id)');
        if ($search !== '') {
            $qbFiltered->andWhere('u.email LIKE :search OR ev.titre LIKE :search OR cert.codeUnique LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        $filtered = (int) $qbFiltered->getQuery()->getSingleScalarResult();
        $orderFields = ['u.email', 'ev.titre', 'cert.dateObtention', 'cert.codeUnique'];
        if ($orderCol >= 0 && $orderCol < \count($orderFields)) {
            $qb->orderBy($orderFields[$orderCol], $orderDir === 'desc' ? 'DESC' : 'ASC');
        } else {
            $qb->orderBy('cert.dateObtention', 'DESC');
        }
        $items = $qb->setFirstResult($start)->setMaxResults($length)->getQuery()->getResult();
        return [$items, $total, $filtered];
    }
}
