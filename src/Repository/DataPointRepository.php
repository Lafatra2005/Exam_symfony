<?php

namespace App\Repository;

use App\Entity\DataPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataPoint>
 */
class DataPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataPoint::class);
    }

    public function findByFilters(array $filters): array
    {
    $qb = $this->createQueryBuilder('d');
    
    if (isset($filters['category']) && $filters['category']) {
        $qb->andWhere('d.category = :category')
           ->setParameter('category', $filters['category']);
    }
    
    if (isset($filters['startDate']) && $filters['startDate']) {
        $qb->andWhere('d.date >= :startDate')
           ->setParameter('startDate', $filters['startDate']);
    }
    
    if (isset($filters['endDate']) && $filters['endDate']) {
        $qb->andWhere('d.date <= :endDate')
           ->setParameter('endDate', $filters['endDate']);
    }
    
    return $qb->getQuery()->getResult();
    }

//    /**
//     * @return DataPoint[] Returns an array of DataPoint objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataPoint
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
