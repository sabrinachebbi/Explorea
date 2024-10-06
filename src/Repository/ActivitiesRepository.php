<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,public PaginatorInterface $paginator)
    {
        parent::__construct($registry, Activity::class);
    }
    public function paginate(int $page =1, int $limit = 6): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return   $this->paginator->paginate(
            $this->createQueryBuilder('a'),
            $page,
            $limit
        );
    }

    //    /**
    //     * @return Activities[] Returns an array of Activities objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Activities
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
