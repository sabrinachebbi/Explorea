<?php

namespace App\Repository;

use App\Entity\Accommodation;
use App\Entity\Country;
use App\Enum\propertyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Accommodation>
 */
class AccommodationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, public PaginatorInterface $paginator)
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


//    Trouver les hébergements par pays.
    public function findByCountry($country)
    {
        return $this->createQueryBuilder('a')
            ->join('a.city', 'c')
            ->join('c.country', 'co')
            ->where('co.name = :country')
            ->setParameter('country', $country)
            ->getQuery()
            ->getResult();
    }

    public function filterAccommodation(
        ?propertyType $propertyType,  // Le premier paramètre doit être le type de propriété
        ?Country $country,      // Le deuxième paramètre doit être l'objet Country
        ?int $priceMin,         // Le troisième paramètre doit être le prix minimum
        ?int $priceMax
    ): array {
        $qb = $this->createQueryBuilder('a')
            ->join('a.city', 'c')
            ->join('c.country', 'co') // Jointure avec le pays via la ville
            ->addSelect('c', 'co'); // Assurez-vous de bien sélectionner les champs nécessaires

        // Filtrer par pays
        if ($country) {
            $qb->andWhere('co = :country')
                ->setParameter('country', $country);
        }
        // Filtrer par type de propriété
        if ($propertyType) {
            $qb->andWhere('a.propertyType = :propertyType')
                ->setParameter('propertyType', $propertyType->value);
        }

        // Filtrer par pays


        // Filtrer par prix minimum
        if ($priceMin) {
            $qb->andWhere('a.priceNight >= :priceMin')
                ->setParameter('priceMin', $priceMin);
        }

        // Filtrer par prix maximum
        if ($priceMax) {
            $qb->andWhere('a.priceNight <= :priceMax')
                ->setParameter('priceMax', $priceMax);
        }

        return $qb->getQuery()->getResult();
    }

}
