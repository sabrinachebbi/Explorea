<?php

namespace App\Repository;

use App\Entity\Accommodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Accommodation>
 */
class AccommodationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry ,public PaginatorInterface $paginator)
    {
        parent::__construct($registry, Accommodation::class);
    }
    public function findByPropertyType($propertyType)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.propertyType = :propertyType')
            ->setParameter('propertyType', $propertyType)
            ->getQuery()
            ->getResult();
    }
//    public function paginate(int $page =1, int $limit = 3):Paginator
//    {
//        return new paginator( $this
//           ->createQueryBuilder('a')
//            ->setFirstResult(($page-1) * $limit)
//            ->setMaxResults($limit)
//            ->getQuery()
//
//        );
//    }
    public function paginate(int $page =1, int $limit = 6): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return   $this->paginator->paginate(
            $this->createQueryBuilder('a'),
            $page,
            $limit
        );
    }

        //    /**
    //     * @return Hebergements[] Returns an array of Hebergements objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hebergements
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
