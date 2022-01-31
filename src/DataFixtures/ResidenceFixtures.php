<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Residence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResidenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 20; $i++) {
            $residence = new Residence();
            $residence->setName('name-'. $i);
            $residence->setAddress('address-'. $i);
            $residence->setCity('city-'. $i);
            $residence->setZipCode('zipcode-'. $i);
            $residence->setCountry('country-'. $i);
            $residence->setInventoryFile('inventory_file-'. $i);
            $residence->setOwnerId($this->getReference('user-'.rand(1,3)));
            $residence->setRepresentativeId($this->getReference('user-'.rand(1,10)));
            $manager->persist($residence);
            $this->addReference('residence-'. $i, $residence);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
