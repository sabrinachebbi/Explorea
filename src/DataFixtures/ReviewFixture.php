<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;

class ReviewFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Générer des avis pour les accommodations
        for ($i = 1; $i < 20; $i++) {
            // Chaque accommodation reçoit au moins un avis
            $numReviews = rand(1, 3);  // Nombre d'avis par accommodation (entre 1 et 3)

            for ($j = 1; $j <= $numReviews; $j++) {
                $review = new Review();

                // Récupérer une accommodation
                $accommodation = $this->getReference('accommodation_' . $i);
                $review->setAccommodation($accommodation);

                // Définir un utilisateur (voyageur) aléatoire comme auteur de l'avis
                $user = rand(0, 1) === 0
                    ? $this->getReference('user_traveler_' . rand(1, 5))
                    : $this->getReference('user_host_' . rand(1, 5));

                $review->setTraveler($user);

                // Définir les autres détails de l'avis
                $review->setNote(rand(1, 5));  // Note aléatoire entre 1 et 5
                $review->setComment('Ceci est un avis pour l\'hébergement ' . $accommodation->getTitle(""));
                $review->setDateReView(new DateTimeImmutable('now'));

                // Lier la réservation associée à l'avis
                $reservation = $this->getReference('reservation_' . rand(1, 10));  // Associer à une réservation existante
                $review->setReservation($reservation);

                $manager->persist($review);
            }
        }

        // Générer des avis pour les activités
        for ($i = 1; $i < 15; $i++) {
            $numReviews = rand(1, 3);  // Nombre d'avis par activité (entre 1 et 3)

            for ($j = 1; $j <= $numReviews; $j++) {
                $review = new Review();

                // Récupérer une activité
                $activity = $this->getReference('activity_' . $i);
                $review->setActivity($activity);

                // Définir un utilisateur (voyageur) aléatoire comme auteur de l'avis
                $traveler = $this->getReference('user_traveler_' . rand(1, 5));
                $review->setTraveler($traveler);

                // Définir les autres détails de l'avis
                $review->setNote(rand(1, 5));  // Note aléatoire entre 1 et 5
                $review->setComment('Ceci est un avis pour l\'activité ' . $activity->getTitle());
                $review->setDateReView(new DateTimeImmutable('now'));

                // Lier la réservation associée à l'avis
                $reservation = $this->getReference('reservation_' . rand(1, 10));  // Associer à une réservation existante
                $review->setReservation($reservation);

                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AccommodationFixture::class,
            ActivityFixture::class,
            ReservationFixture::class,
        ];
    }
}
