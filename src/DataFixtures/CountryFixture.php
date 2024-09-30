<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $countries = [
            'France',
            'Thailand',
            'Italy',
            'Spain',
            'Turkey',
            'Greece'
        ];


        foreach ($countries as $countryName) {
            $country = new Country();
            $country->setName($countryName);

            $this->addReference('country_' . strtolower($countryName), $country);


            $manager->persist($country);
        }


        $manager->flush();
    }
}
