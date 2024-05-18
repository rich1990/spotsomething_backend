<?php

namespace App\Repository;

use App\Entity\Flats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Flats>
 */
class FlatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flats::class);
    }

    public function findAllOrderedByField($field, $order, $offset, $limit)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->orderBy(new Expr\OrderBy('p.' . $field, $order));
        
        // Set the maximum number of results to return
        $qb->setMaxResults($limit);

        // Set the offset (starting index) for pagination
        $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }
}
