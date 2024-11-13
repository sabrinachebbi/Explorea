<?php

namespace App\Repository;

use App\Entity\Activity;
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
    public function findReservationsByActivity(Activity $activity): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.activities', 'a')
            ->where('a = :activity')
            ->setParameter('activity', $activity)
            ->getQuery()
            ->getResult();
    }
    // src/Repository/ReservationRepository.php
    public function findReservationByTravelerAndActivity($voyageur, $activity)
    {
        return $this->createQueryBuilder('r')
            ->join('r.activities', 'a')
            ->where('r.traveler = :voyageur')
            ->andWhere('a = :activity')
            ->setParameter('voyageur', $voyageur)
            ->setParameter('activity', $activity)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
