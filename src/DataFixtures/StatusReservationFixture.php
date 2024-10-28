<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Enum\statusResv;
use App\Entity\ReservationStatus;

class StatusReservationFixture extends Fixture
{
    public const PENDING_STATUS = 'PENDING_STATUS';

    public function load(ObjectManager $manager): void
    {
        $statuses = [
            statusResv::PENDING,
            statusResv::CONFIRM,
            statusResv::CANCELLED
        ];

        foreach ($statuses as $status) {
            $reservationStatus = new ReservationStatus();
            $reservationStatus->setStatus($status);

            $manager->persist($reservationStatus);

            // Ajoutez une référence pour PENDING
            if ($status === statusResv::PENDING) {
                $this->addReference(self::PENDING_STATUS, $reservationStatus);
            }
        }

        $manager->flush();
    }
}
