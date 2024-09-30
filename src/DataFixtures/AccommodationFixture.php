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

        $cities = [
            $this->getReference('city_Paris'),
            $this->getReference('city_Nice'),
            $this->getReference('city_Marseille'),
            $this->getReference('city_Ibiza'),
            $this->getReference('city_Rome'),
            $this->getReference('city_Istanbul'),
            $this->getReference('city_Chamonix'),
            $this->getReference('city_Seville'),
            $this->getReference('city_Bangkok'),
            $this->getReference('city_cappadocia')
        ];

        $titles = [
            'Appartement cosy à Paris avec vue sur la Tour Eiffel',
            'Villa moderne avec piscine à Antibes',
            'Charmant studio au cœur de Nice',
            'Maison de vacances en bord de mer à Ibiza',
            'Appartement lumineux à Rome près du Colisée',
            'Loft spacieux avec terrasse à Istanbul',
            'Chalet traditionnel à Chamonix avec vue sur les montagnes',
            'Villa luxueuse avec piscine à Séville',
            'Petit appartement confortable à Bangkok',
            'Maison de campagne tranquille à Cappadocia',
        ];

        for ($i = 0; $i < 10; $i++) {
            $accommodation = new Accommodation();
            $accommodation->setTitle($titles[$i])
                ->setDescription($faker->text(200))
                ->setAddress($faker->address())
                ->setPriceNight($faker->randomFloat(2, 50, 300))
                ->setNbGuests($faker->numberBetween(1, 10))
                ->setNbRooms($faker->numberBetween(1, 5))
                ->setCreateDate(new \DateTimeImmutable())
                ->setUpdateDate(new \DateTimeImmutable())
                ->setPropertyType($faker->randomElement([propertyType::Apartment, propertyType::House]))
                ->setHost($hosts[$faker->numberBetween(0, count($hosts) - 1)])
                ->setCity($cities[$i]);

            $manager->persist($accommodation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CityFixture::class,
        ];
    }
}
