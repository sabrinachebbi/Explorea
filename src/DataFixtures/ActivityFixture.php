<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActivityFixture extends Fixture implements DependentFixtureInterface
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
            $this->getReference('city_Barcelona'),
            $this->getReference('city_Rome'),
            $this->getReference('city_Ibiza'),
            $this->getReference('city_Istanbul'),
            $this->getReference('city_Phuket'),
            $this->getReference('city_cappadocia'),
            $this->getReference('city_Chamonix'),
            $this->getReference('city_Trabzon'),
            $this->getReference('city_Marseille'),
            $this->getReference('city_Chiang Mai'),
            $this->getReference('city_Venice'),
            $this->getReference('city_Antalya'),
            $this->getReference('city_Bangkok'),
            $this->getReference('city_Nice'),
        ];

        $categories = [
            $this->getReference('category_Sports Extrêmes'),
            $this->getReference('category_Nautique'),
            $this->getReference('category_Aventure'),
            $this->getReference('category_Culturel'),
            $this->getReference('category_Gastronomie'),
            $this->getReference('category_Guide touristique'),
            $this->getReference('category_Ateliers Créatifs'),
            $this->getReference('category_Photographie'),
        ];


        $titles = [
            'Randonnée guidée dans les montagnes de Chamonix',
            'Cours de cuisine italienne traditionnelle à Rome',
            'Balade en bateau sur la Seine à Paris',
            'Cours de surf à Barcelone',
            'Excursion en montgolfière à Cappadoce',
            'Visite guidée du Colisée à Rome',
            'Tour gastronomique des marchés locaux à Istanbul',
            'Excursion en kayak autour des îles d’Ibiza',
            'Séance de yoga au lever du soleil à Phuket',
            'Dégustation de vins dans les vignes de Bordeaux',
            'Excursion à vélo à travers les calanques de Marseille',
            'Balade en segway à travers les rues historiques de Séville',
            'Cours de voile à Valence',
            'Safari en quad dans les montagnes d’Antalya',
            'Tour de Tuk-tuk à travers Bangkok',
        ];

        for ($i = 0; $i < 15; $i++) {
            $activity = new Activity();
            $activity->setTitle($titles[$i])
                ->setDescription($faker->text(255))
                ->setPrice($faker->randomFloat(2, 20, 300))
                ->setCreateDate(new \DateTimeImmutable())
                ->setUpdateDate(new \DateTimeImmutable())
                ->setAddress($faker->address())
                ->setCategory($categories[$faker->numberBetween(0, count($categories) - 1)])
                ->setHost($hosts[$faker->numberBetween(0, count($hosts) - 1)])
                ->setCity($cities[$i]);

            $manager->persist($activity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CityFixture::class,
            CategoryFixture::class,
        ];
    }
}
