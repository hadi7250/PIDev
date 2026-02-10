<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * Optional: Find all quizzes for a specific course ID
     * @return Quiz[]
     */
    public function findAllByCourse(int $courseId): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.cours = :val')
            ->setParameter('val', $courseId)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}