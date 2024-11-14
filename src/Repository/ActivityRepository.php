<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Country;
use App\Enum\propertyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,public PaginatorInterface $paginator)
    {
        parent::__construct($registry, Activity::class);
    }
    public function paginate(int $page = 1, int $limit = 6): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

    public function findByCountry($country)
    {
        return $this->createQueryBuilder('act')
            ->join('act.city', 'c')
            ->join('c.country', 'co')
            ->where('co.name = :country')
            ->setParameter('country', $country)
            ->getQuery()
            ->getResult();
    }
    public function filterActivity(
        ?Category $category,
        ?int $duration,
        ?Country $country,
        ?int $priceMin,
        ?int $priceMax
    ): array {
        $qb = $this->createQueryBuilder('ac')
            ->join('ac.city', 'c')
            ->join('c.country', 'co')
            ->addSelect('c', 'co');

        // Filtrer par pays
        if ($country) {
            $qb->andWhere('co = :country')
                ->setParameter('country', $country);
        }
        // Filtrer par catégorie
        if ($category) {
            $qb->andWhere('ac.category = :category')
                ->setParameter('category', $category);
        }
        // Filtrer par durée
        if ($duration) {
            $qb->andWhere('ac.duration = :duration')
                ->setParameter('duration', $duration);
        }

        // Filtrer par prix minimum
        if ($priceMin) {
            $qb->andWhere('ac.price >= :priceMin')
                ->setParameter('priceMin', $priceMin);
        }

        // Filtrer par prix maximum
        if ($priceMax) {
            $qb->andWhere('ac.price <= :priceMax')
                ->setParameter('priceMax', $priceMax);
        }

        return $qb->getQuery()->getResult();
    }


}
