<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findExistingReservation(int $carId, \DateTime $startTime, \DateTime $endTime): ?Reservation
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.car = :carId')
           ->andWhere('r.startTime < :endTime AND r.endTime > :startTime')
           ->setParameter('carId', $carId)
           ->setParameter('startTime', $startTime)
           ->setParameter('endTime', $endTime)
           ->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
