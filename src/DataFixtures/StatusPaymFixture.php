<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Enum\statusPaym;

use App\Entity\StatusPayment;

class StatusPaymFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuses = [
            statusPaym::PAYMENT_PENDING,
            statusPaym::PAYMENT_APPROVED,
            statusPaym::PAYMENT_Cancelled
        ];
        foreach ($statuses as $status) {
            $paymentStatus = new StatusPayment();
            $paymentStatus->setStatus($status);

            $manager->persist($paymentStatus);
        }
        $manager->flush();
    }
}
