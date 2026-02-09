<?php

namespace App\Repository;

use App\Entity\Competence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competence>
 */
class CompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competence::class);
    }

    public function search(string $keyword, ?string $category = null): array
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.name LIKE :keyword')
            ->orWhere('c.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');

        if ($category) {
            $query->andWhere('c.category = :category')
                  ->setParameter('category', $category);
        }

        return $query->orderBy('c.name', 'ASC')
                     ->getQuery()
                     ->getResult();
    }
}
