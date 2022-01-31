<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstname('firstname-'. $i);
            $user->setLastname('lastname-'. $i);
            $user->setRole('role-'. $i);
            $user->setEmail('email-'. $i);
            $user->setPassword('password-'. $i);
            $user->setIsVerified('isverified-'. $i);
            $manager->persist($user);
            $this->addReference('user-'. $i, $user);
        }

        $manager->flush();
    }

}
