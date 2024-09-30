<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CityFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $cities = [
            ['name' => 'Paris', 'postalCode' => 75000, 'country' => 'france'],
            ['name' => 'Nice', 'postalCode' => 6000, 'country' => 'france'],
            ['name' => 'Marseille', 'postalCode' => 13000, 'country' => 'france'],
            ['name' => 'Chamonix', 'postalCode' => 74400, 'country' => 'france'],

            ['name' => 'Barcelona', 'postalCode' => 8001, 'country' => 'spain'],
            ['name' => 'Seville', 'postalCode' => 41001, 'country' => 'spain'],
            ['name' => 'Valencia', 'postalCode' => 46001, 'country' => 'spain'],
            ['name' => 'Ibiza', 'postalCode' => 7800, 'country' => 'spain'],

            ['name' => 'Istanbul', 'postalCode' => 34000, 'country' => 'turkey'],
            ['name' => 'Antalya', 'postalCode' => 7000, 'country' => 'turkey'],
            ['name' => 'cappadocia', 'postalCode' => 50180, 'country' => 'turkey'],
            ['name' => 'Trabzon', 'postalCode' => 61000, 'country' => 'turkey'],
            ['name' => 'Bodrum', 'postalCode' => 48400, 'country' => 'turkey'],

            ['name' => 'Bangkok', 'postalCode' => 10100, 'country' => 'thailand'],
            ['name' => 'Chiang Mai', 'postalCode' => 50000, 'country' => 'thailand'],
            ['name' => 'Phuket', 'postalCode' => 83000, 'country' => 'thailand'],

            ['name' => 'Athens', 'postalCode' => 10552, 'country' => 'greece'],
            ['name' => 'Santorini', 'postalCode' => 84700, 'country' => 'greece'],
            ['name' => 'Rome', 'postalCode' => 00100, 'country' => 'italy'],
            ['name' => 'Venice', 'postalCode' => 30100, 'country' => 'italy'],
        ];

        foreach ($cities as $cityData) {
            $city = new City();
            $city->setName($cityData['name']);
            $city->setPostalCode($cityData['postalCode']);


            $this->addReference('city_' . ($cityData['name']), $city);


            $country = $this->getReference('country_' . $cityData['country']);
            $city->setCountry($country);

            $manager->persist($city);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CountryFixture::class,
        ];
    }
}
