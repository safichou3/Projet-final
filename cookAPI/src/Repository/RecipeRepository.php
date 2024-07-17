<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function addPaginate(QueryBuilder $b, $page, $limit): QueryBuilder
    {
        return $b->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);
    }

    public function addOrder(QueryBuilder $q, $order)
    {
        return $q->orderBy('r.title', mb_strtoupper($order));
    }

    public function findAllOrdered($order)
    {
        $q = $this->createQueryBuilder('r')->select('r,c')->leftJoin('r.category', 'c');
        $q = $this->addOrder($q, $order);
        return $q->getQuery()->getResult();
    }

    public function findAllOrderedPaginate($page, $limit, $order)
    {
        $q = $this->createQueryBuilder('r')->select('r,c')->leftJoin('r.category', 'c');
        $q = $this->addOrder($q, $order);
        $q = $this->addPaginate($q, $page, $limit);
        return $q->getQuery()->getResult();
    }
    ////////////
    // COUNT
    /////////////
    public function countAll(): int
    {
        return $this->createQueryBuilder('r')->select('count(r.id)')->getQuery()->getSingleScalarResult();
    }
}
