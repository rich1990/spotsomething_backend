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

    /**
     * Find all flats ordered by a specified field, with optional search, pagination, and sorting.
     *
     * @param string $field  The field to order by.
     * @param string $order  The order direction (ASC or DESC).
     * @param int    $offset The offset (starting index) for pagination.
     * @param int    $limit  The maximum number of results to return.
     * @param string $search The search term to filter by.
     *
     * @return array The array of results.
     */
    public function findAllOrderedByField($field, $order, $offset, $limit, $search)
    {
        $qb = $this->createQueryBuilder('p');

        // Add search condition if search term is provided
        if (strlen((string) $search) > 0) {
            $qb->andWhere($qb->expr()->like('p.city', ':search'))
               ->setParameter('search', '%' . $search . '%');
        }

        // Order the results by the specified field
        $qb->orderBy(new Expr\OrderBy('p.' . $field, $order));

        // Set the maximum number of results to return
        $qb->setMaxResults($limit);

        // Set the offset (starting index) for pagination
        $qb->setFirstResult($offset);

        // Execute the query and return the results
        return $qb->getQuery()->getResult();
    }
}