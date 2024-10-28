<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail('host'.$i.'@gmail.com');
            $user->setRoles(['ROLE_USER', 'ROLE_HOST']);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'password123'
            ));

            $this->addReference('user_host_'.$i, $user);
            $manager->persist($user);
        }

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail('traveler'.$i.'@gmail.com');
            $user->setRoles(['ROLE_USER', 'ROLE_TRAVELER']);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'password123'
            ));

            $this->addReference('user_traveler_'.$i, $user);
            $manager->persist($user);
        }
        // Création d'un administrateur
        $adminUser = new User();
        $adminUser->setEmail('sabrina.chebbi@gmail.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($this->passwordHasher->hashPassword(
            $adminUser,
            'password'
        ));

        $this->addReference('admin_user', $adminUser);
        $manager->persist($adminUser);

        $manager->flush();
    }
}
