<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class PictureFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // URLs d'images pour les hébergements
        $accommodationImageURLs = [
            'logement13.jpeg',
            'logement19.jpeg',
            'logement11.jpeg',
            'logement8.webp',
            'logement21.webp',
            'logement5.webp',
            'logement14.webp',
            'logement1 - Copie.webp',
            'logement12.jpeg',
            'logement4.jpeg',
            'logement16webp.webp',
            'logement6.webp',
            'logement22.webp',
            'logement24.jpeg',
            'logement27webp.webp',
            'logement25.webp',
            'logement30.jpeg',
            'logement23.jpeg',
            'logement26.jpeg',
            'logement29.jpeg',
            'logement31.webp',
            'logement20.webp',
            'logement10.jpeg',
            'logement17webp.webp',
            'logement7.webp',
            'logement9webp.webp',
            'logement2.webp',
            'logement18.webp',
            'logement15.webp',
            'logement35.webp',
            'logement34.webp',
            'logement36.webp',
            'logement37jpeg.jpeg',
            'logement38.jpeg',
            'logement39.webp',
            'logement40jpeg.jpeg',
        ];

        // URLs d'images pour les activités
        $activityImageURLs = [
            'activity1.jpg',
            'activity2.jpg',
            'activity3.jpeg',
            'activity4.jpeg',
            'activity5.webp',
            'activity6.jpeg',
            'activity7.jpeg',
            'activity8.jpeg',
            'activity9.jpeg',
            'activity10.webp',
            'activity11.jpeg',
            'activity12.jpeg',
            'activity13.jpeg',
            'activity14.jpeg',
            'activity15 (2).jpeg',
            'activity16.jpeg',
            'activity18.jpeg',
            'activity19.jpeg',
        ];

        // Création des images pour les hébergements
        for ($i = 0; $i < 20; $i++) {
            $accommodation = $this->getReference('accommodation_' . $i);

            for ($j = 0; $j < 3; $j++) {
                $picture = new Picture();
                $picture->setName($accommodationImageURLs[array_rand($accommodationImageURLs)]); // Sélectionner une image existante
                $picture->setAccommodation($accommodation);
                $picture->setUpdateAt(new \DateTimeImmutable()); // Date de mise à jour
                $manager->persist($picture);
            }
        }


        // Création des images pour les activités
        for ($i = 0; $i < 15; $i++) {
            $activity = $this->getReference('activity_' . $i);

            $picture = new Picture();
            $picture->setName($activityImageURLs[array_rand($activityImageURLs)]);
            $picture->setActivity($activity);
            $picture->setUpdateAt(new \DateTimeImmutable());
            $manager->persist($picture);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccommodationFixture::class,
            ActivityFixture::class,
        ];
    }
}
