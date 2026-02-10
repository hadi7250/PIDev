<?php

namespace App\Repository;

use App\Entity\Attempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attempt>
 *
 * @method Attempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attempt[]    findAll()
 * @method Attempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attempt::class);
    }

    /**
     * Optional: Find attempts sorted by newest first
     * @return Attempt[]
     */
    public function findLatestAttempts(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.startedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}