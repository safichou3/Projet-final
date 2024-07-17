<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function addOrder(QueryBuilder $q, $order)
    {
        return $q->orderBy('c.title', mb_strtoupper($order));
    }

    public function addPaginate(QueryBuilder $q, $page, $limit)
    {
        return $q->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);
    }

    public function findAllOrdered($order)
    {
        $q = $this->createQueryBuilder('c')->select('c');
        $q = $this->addOrder($q, $order);
        return $q->getQuery()->getResult();
    }

    public function findOrderedPaginate($page, $limit, $order)
    {
        $q = $this->createQueryBuilder('c')->select('c');
        $q = $this->addOrder($q, $order);
        $q = $this->addPaginate($q, $page, $limit);
        return $q->getQuery()->getResult();
    }


    public function countAll(): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
