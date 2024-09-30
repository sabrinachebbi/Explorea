<?php

namespace App\DataFixtures;

use App\Entity\UserProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Enum\GenderEnum;

class ProfilUserFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();


        for ($i = 1; $i <= 5; $i++) {
            $profile = new UserProfile();

            $profile->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setPhone($faker->phoneNumber())
                ->setDateBirth($faker->dateTimeBetween('-40 years', '-18 years'))
                ->setAddress($faker->address())
                ->setUser($this->getReference('user_host_' . $i));


            if ($i % 2 == 0) {
                $profile->setGender(GenderEnum::Male);
            } else {
                $profile->setGender(GenderEnum::Female);
            }

            $manager->persist($profile);
        }

        for ($i = 1; $i <= 5; $i++) {
            $profile = new UserProfile();

            $profile->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setPhone($faker->phoneNumber())
                ->setDateBirth($faker->dateTimeBetween('-40 years', '-18 years'))
                ->setAddress($faker->address())
                ->setUser($this->getReference('user_traveler_' . $i));


            if ($i % 2 == 0) {
                $profile->setGender(GenderEnum::Male);
            } else {
                $profile->setGender(GenderEnum::Female);
            }

            $manager->persist($profile);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
