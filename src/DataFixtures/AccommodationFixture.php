<?php

namespace App\DataFixtures;

use App\Entity\Accommodation;
use App\Enum\propertyType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class AccommodationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $hosts = [
            $this->getReference('user_host_1'),
            $this->getReference('user_host_2'),
            $this->getReference('user_host_3'),
            $this->getReference('user_host_4'),
            $this->getReference('user_host_5')
        ];

        $countries = [
            $this->getReference('country_france'),
            $this->getReference('country_thailand'),
            $this->getReference('country_italy'),
            $this->getReference('country_spain'),
            $this->getReference('country_turkey'),
            $this->getReference('country_greece'),
            ];

        $titles = [
            'Appartement cosy à Paris ',
            'Villa moderne avec piscine ',
            'Charmant studio au cœur de Nice',
            'Maison  en bord de mer à Ibiza',
            'Appartement lumineux à Rome ',
            'Loft spacieux avec terrasse à Istanbul',
            'Chalet traditionnel à Chamonix avec vue sur les montagnes',
            'Villa luxueuse avec piscine à Séville',
            'Petit appartement confortable à Bangkok',
            'Maison de campagne  à Cappadocia',
            'Appartement élégant à Milan avec vue sur la cathédrale',
            'Maison rustique à la campagne en Toscane',
            'Penthouse moderne à Barcelone avec terrasse privée',
            'Cottage pittoresque au bord du lac de Côme',
            'Villa contemporaine avec piscine sur la Côte dAzur',
            'Château rénové avec jardin à Florence',
            'Appartement charmant dans le centre historique de Madrid',
            'Cabane en bois isolée dans les montagnes',
             'Studio design à Santorino avec vue sur la mer',
              'Maison de luxe en bord de mer' ,

        ];

        for ($i = 0; $i < 20; $i++) {
            $accommodation = new Accommodation();
            $accommodation->setTitle($titles[$i])
                ->setDescription($faker->text(200))
                ->setAddress($faker->unique()->address())
                ->setPriceNight($faker->randomFloat(2, 50, 300))
                ->setNbGuests($faker->numberBetween(1, 10))
                ->setNbRooms($faker->numberBetween(1, 5))
                ->setCreateDate(new \DateTimeImmutable())
                ->setUpdateDate(new \DateTimeImmutable())
                ->setPropertyType($faker->randomElement([propertyType::Apartment, propertyType::House]))
                ->setHost($hosts[$faker->numberBetween(0, count($hosts) - 1)])
                ->setCountry($countries[array_rand($countries)]);

            $this->addReference('accommodation_' . $i, $accommodation);

            $manager->persist($accommodation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CountryFixture::class,
        ];
    }
}
