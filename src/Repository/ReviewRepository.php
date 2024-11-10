<?php

namespace App\Repository;

use App\Entity\Accommodation;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findByAccommodation(Accommodation $accommodation): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.reservation', 'res')
            ->where('res.accommodation = :accommodation')
            ->setParameter('accommodation', $accommodation)
            ->getQuery()
            ->getResult();
    }
}
