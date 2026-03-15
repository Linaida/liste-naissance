<?php

namespace App\Repository;

use App\DTO\PaginationDTO;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearch(PaginationDTO $pagination): array
    {
        $qb = $this->createQueryBuilder('a')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->limit);

        foreach ($pagination->filters as $filter) {
            $qb->andWhere(sprintf('a.%s %s :%s', $filter->field, $filter->operator, $filter->field))
                ->setParameter($filter->field, $filter->value);
        }

        foreach ($pagination->orders as $order) {
            $qb->addOrderBy(sprintf('a.%s', $order->field), $order->direction);
        }

        return $qb->getQuery()->getResult();
    }
}
