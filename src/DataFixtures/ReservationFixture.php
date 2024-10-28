<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;
use App\Entity\Accommodation;
use App\Entity\Activity;
use App\DataFixtures\StatusReservationFixture;
use DateTimeImmutable;

class ReservationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $reservation = new Reservation();

            $userType = rand(0, 1) === 0 ? 'traveler' : 'host';
            $user = $this->getReference('user_' . $userType . '_' . rand(1, 5));
            $reservation->setTraveler($user);


            $accommodation = $this->getReference('accommodation_' . rand(1, 5));
            $reservation->setAccommodation($accommodation);


            for ($j = 1; $j <= rand(1, 3); $j++) {
                $activity = $this->getReference('activity_' . rand(1, 5));
                $reservation->addActivity($activity);
            }


            $reservation->setDepartureDate(new DateTimeImmutable('2024-10-15'));
            $reservation->setReturnDate(new DateTimeImmutable('2024-10-20'));
            $reservation->setDateCreation(new DateTimeImmutable('now'));
            $reservation->setDateModification(new DateTimeImmutable('now'));
            $reservation->setVoyagerNb(rand(1, 4));


            $total = $reservation->calculateTotal();
            $reservation->setTotal($total);


            $status = $this->getReference(StatusReservationFixture::PENDING_STATUS);
            $reservation->setStatus($status);
            $this->addReference('reservation_' . $i, $reservation);

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
