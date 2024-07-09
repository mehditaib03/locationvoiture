<?php

namespace App\Repository;

use App\Entity\Car;
use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findAvailableCars(DateTimeInterface $startTime, DateTimeInterface $endTime)
    {
        $queryBuilder = $this->createQueryBuilder('car')
            ->leftJoin('car.reservations', 'reservation')
            ->where('reservation.id IS NULL OR (reservation.startTime NOT BETWEEN :start AND :end AND reservation.endTime NOT BETWEEN :start AND :end)')
            ->setParameter('start', $startTime)
            ->setParameter('end', $endTime)
            ->groupBy('car.id');
    
        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Car[] Returns an array of Car objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Car
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
