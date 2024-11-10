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
            'img1.jpg',
            'img2.jpg',
            'img3.jpg',
            'img4.jpg',
            'img7.jpg',
            'img8.jpg',
            'img9.jpg',
            'img10.jpg',
            'img11.jpg',
            'img12.jpg',
            'img13.jpg',
            'img14.jpg',
            'img15.jpg',
            'img16.jpg',
            'img17.jpg',
            'img18.jpg',
            'img19.jpg',
            'img20.jpg',
            'img21.jpg',
            'img22.jpg',
            'img24.jpg',
            'img25.jpg',
            'img27.jpg',
            'img28.jpg',
            'img29.jpg',
            'img30.jpg',
        ];

        // URLs d'images pour les activités
        $activityImageURLs = [
            'nature.jpg',
            'photographie.jpg',
            'sport-extreme.avif',
            'sport-nautique.jpg',
            'parachute.avif',
            'guide-touristique.jpg',
            'gastronomie.jpg',
            'detente.jpg',
            'aventure.jpg',
            'ateliers-creatifs.jpg',
            'image1.png',
            'image3.png',
        ];

        // Création des images pour les hébergements
        for ($i = 0; $i < 20; $i++) {
            $accommodation = $this->getReference('accommodation_' . $i);

            if ($accommodation) {
                for ($j = 0; $j < 3; $j++) {
                    $picture = new Picture();
                    $picture->setName($accommodationImageURLs[array_rand($accommodationImageURLs)]); // Sélectionner une image existante
                    $picture->setAccommodation($accommodation);
                    $picture->setUpdateAt(new \DateTimeImmutable()); //  Date de mise à jour
                    $manager->persist($picture);
                }
            }
        }

        // Création des images pour les activités
        for ($i = 0; $i < 15; $i++) {
            $activity = $this->getReference('activity_' . $i);

            if ($activity) {
                $picture = new Picture();
                $picture->setName($activityImageURLs[array_rand($activityImageURLs)]);
                $picture->setActivity($activity);
                $picture->setUpdateAt(new \DateTimeImmutable());
                $manager->persist($picture);
            }
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
