<?php

namespace App\Repository;

use App\Entity\EvaluationExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EvaluationExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationExercise::class);
    }

    public function findByEvaluation($evaluationId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.evaluation = :evalId')
            ->setParameter('evalId', $evaluationId)
            ->getQuery()
            ->getResult();
    }

    public function findByLanguage($language)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.language = :lang')
            ->setParameter('lang', $language)
            ->getQuery()
            ->getResult();
    }
}
