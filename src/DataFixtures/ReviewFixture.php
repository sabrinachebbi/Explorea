<?php
namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReviewFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Commentaires pour les hébergements
        $accommodationComments = [
            "Excellent séjour, l'hébergement était parfait.",
            "Très confortable et bien situé.",
            "Le logement correspondait exactement aux photos.",
            "L'accueil était chaleureux, je recommande cet hébergement.",
            "Propre et bien équipé, un excellent choix.",
        ];

        // Commentaires pour les activités
        $activityComments = [
            "Super activité, très bien encadrée.",
            "Moment inoubliable, je recommande vivement !",
            "Excellente organisation, tout s'est bien passé.",
            "Activité très enrichissante et amusante.",
            "Une expérience incroyable, nous avons adoré.",
        ];

        // Générer des avis pour les réservations associées à des hébergements
        for ($i = 1; $i <= 10; $i++) {
            $review = new Review();
            $review->setNote($faker->numberBetween(1, 5));
            $review->setComment($accommodationComments[array_rand($accommodationComments)]);
            $review->setDateReView(new \DateTimeImmutable());

            /** @var Reservation $reservation */
            $reservation = $this->getReference('reservation_accommodation_' . $i); // Référence pour les réservations d'hébergement
            $review->setReservation($reservation);

            $manager->persist($review);
        }

        // Générer des avis pour les réservations associées à des activités
        for ($i = 1; $i <= 10; $i++) {
            $review = new Review();
            $review->setNote($faker->numberBetween(1, 5));
            $review->setComment($activityComments[array_rand($activityComments)]);
            $review->setDateReView(new \DateTimeImmutable());

            /** @var Reservation $reservation */
            $reservation = $this->getReference('reservation_activity_' . $i); // Référence pour les réservations d'activités
            $review->setReservation($reservation);

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationFixture::class,
        ];
    }
}
