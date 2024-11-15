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
            'Randonnée guidée dans les montagnes ',
            'Cours de cuisine italienne traditionnelle ',
            'Balade en bateau sur la Seine ',
            'Cours de surf ',
            'Excursion en montgolfière ',
            'Visite guidée du Colisée ',
            'Tour gastronomique des marchés locaux ',
            'Excursion en kayak autour des îles ',
            'Séance de yoga au lever du soleil ',
            'Dégustation de vins dans les vignes ',
            'Excursion à vélo à travers les calanques ',
            'Balade en segway à travers les rues historiques ',
            'Cours de voile ',
            'Safari en quad dans les montagnes ',
            'photographie ',
        ];

        for ($i = 0; $i < 15; $i++) {
            $activity = new Activity();
            $activity->setTitle($titles[$i])
                ->setDescription($faker->text(255))
                ->setPrice($faker->randomFloat(2, 20, 300))
                ->setCreateDate(new \DateTimeImmutable())
                ->setAddress(('Non spécifiée'))
                ->setCategory($categories[$faker->numberBetween(0, count($categories) - 1)])
                ->setHost($hosts[$faker->numberBetween(0, count($hosts) - 1)])
                ->setCity($cities[$i])
                ->setDuration($faker->numberBetween(1, 7));

            $this->addReference('activity_' . $i, $activity);

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
