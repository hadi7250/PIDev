<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    public function search(string $keyword, ?string $type = null): array
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.title LIKE :keyword')
            ->orWhere('e.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');

        if ($type) {
            $query->andWhere('e.type = :type')
                  ->setParameter('type', $type);
        }

        return $query->orderBy('e.evaluationDate', 'DESC')
                     ->getQuery()
                     ->getResult();
    }
}
