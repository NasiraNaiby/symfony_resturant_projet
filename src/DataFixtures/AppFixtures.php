<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new Users();
            $user->setUserNom('User ' . $i);
            $user->setEmail('user' . $i . '@example.com'); // Ensure unique email
            $user->setTel('123-456-789' . $i);
            $user->setAddresse('Address ' . mt_rand(10, 100));

            // Hash the password
            $hashedPassword = $this->passwordHasher->hashPassword($user, '1234');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
