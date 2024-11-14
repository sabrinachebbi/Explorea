<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\AccommodationFixture;
use App\DataFixtures\ActivityFixture;
use App\DataFixtures\StatusReservationFixture;
use DateTimeImmutable;

class ReservationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Générer des réservations pour les hébergements
        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservation();

            // Associer un utilisateur voyageur
            $user = $this->getReference('user_traveler_' . rand(1, 5));
            $reservation->setTraveler($user);

            // Associer un hébergement spécifique
            $accommodation = $this->getReference('accommodation_' . rand(1, 5));
            $reservation->setAccommodation($accommodation);

            // Définir les dates et autres détails de la réservation
            $reservation->setDepartureDate(new DateTimeImmutable('2024-10-15'));
            $reservation->setReturnDate(new DateTimeImmutable('2024-10-20'));
            $reservation->setDateCreation(new DateTimeImmutable('now'));
            $reservation->setDateModification(new DateTimeImmutable('now'));
            $reservation->setVoyagerNb(rand(1, 4));

            // Calculer et définir le total
            $total = $reservation->calculateTotal();
            $reservation->setTotal($total);

            // Associer un statut
            $status = $this->getReference(StatusReservationFixture::PENDING_STATUS);
            $reservation->setStatus($status);

            // Ajouter une référence spécifique pour les réservations d'hébergements
            $this->addReference('reservation_accommodation_' . $i, $reservation);

            $manager->persist($reservation);
        }

        // Générer des réservations pour les activités
        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservation();

            // Associer un utilisateur voyageur
            $user = $this->getReference('user_traveler_' . rand(1, 5));
            $reservation->setTraveler($user);

            // Associer des activités aléatoires à la réservation
            for ($j = 1; $j <= rand(1, 3); $j++) {
                $activity = $this->getReference('activity_' . rand(1, 5));
                $reservation->addActivity($activity);
            }

            // Définir les dates et autres détails de la réservation
            $reservation->setDepartureDate(new DateTimeImmutable('2024-10-15'));
            $reservation->setDateCreation(new DateTimeImmutable('now'));
            $reservation->setDateModification(new DateTimeImmutable('now'));
            $reservation->setVoyagerNb(rand(1, 4));

            // Calculer et définir le total
            $total = $reservation->calculateTotal();
            $reservation->setTotal($total);

            // Associer un statut
            $status = $this->getReference(StatusReservationFixture::PENDING_STATUS);
            $reservation->setStatus($status);

            // Ajouter une référence spécifique pour les réservations d'activités
            $this->addReference('reservation_activity_' . $i, $reservation);

            $manager->persist($reservation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AccommodationFixture::class,
            ActivityFixture::class,
            StatusReservationFixture::class,
        ];
    }
}
