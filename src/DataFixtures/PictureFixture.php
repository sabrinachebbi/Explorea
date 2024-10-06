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
        $faker = Factory::create();


        $accommodationImageURLs = [
            'images/img1.jpg',
            'images/img2.jpg',
            'images/img3.jpg',
            'images/img4.jpg',
            'images/img7.jpg',
            'images/img8.jpg',
            'images/img9.jpg',
            'images/img10.jpg',
            'images/img11.jpg',
            'images/img12.jpg',
            'images/img13.jpg',
            'images/img14.jpg',
            'images/img15.jpg',
            'images/img16.jpg',
            'images/img17.jpg',
            'images/img18.jpg',
            'images/img19.jpg',
            'images/img20.jpg',
            'images/img21.jpg',
            'images/img22.jpg',
            'images/img24.jpg',
            'images/img25.jpg',
            'images/img27.jpg',
            'images/img28.jpg',
            'images/img29.jpg',
            'images/img30.jpg',
        ];

        $activityImageURLs = [
            'images/nature.jpg',
            'images/photograohie.jpg',
            'images/sport-extreme.avif',
            'images/sport-nautique.jpg',
            'images/parachute.avif',
            'images/parachute.avif',
            'images/guide-touristique.jpg',
            'images/gastronomie.jpg',
            'images/detente.jpg',
            'images/aventure.jpg',
            'images/ateliers-creatifs.jpg',
            'images/image1.png',
            'images/image3.png',
        ];

        for ($i = 0; $i < 20; $i++) {
            $accommodation = $this->getReference('accommodation_' . $i);


            if ($accommodation) {
                for ($j = 0; $j < 3; $j++) {
                    $picture = new Picture();
                    $picture->setImageURL($faker->randomElement($accommodationImageURLs));
                    $picture->setAccommodationPictures($accommodation);
                    $manager->persist($picture);
                }
            }
        }

        // Création des images pour les activités
        for ($i = 0; $i < 15; $i++) {
            $activity = $this->getReference('activity_' . $i);

            // Vérifier que l'activité existe
            if ($activity) {
                    $picture = new Picture();
                    $picture->setImageURL($faker->randomElement($activityImageURLs));
                    $picture->setActivityPictures($activity);
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
