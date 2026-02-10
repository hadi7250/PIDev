<?php

namespace App\Repository;

use App\Entity\Discussion;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discussion>
 */
class DiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discussion::class);
    }

    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.title LIKE :search')
            ->orWhere('d.content LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(?Category $category): array
    {
        if (!$category) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('d')
            ->where('d.category = :category')
            ->setParameter('category', $category)
            ->orderBy('d.isPinned', 'DESC')
            ->addOrderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findMostViewed(int $limit = 10): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function incrementViews(Discussion $discussion): void
    {
        $this->createQueryBuilder('d')
            ->update()
            ->set('d.views', 'd.views + 1')
            ->where('d.id = :id')
            ->setParameter('id', $discussion->getId())
            ->getQuery()
            ->execute();
    }
}
