<?php

namespace App\DataFixtures;

use App\Entity\Rent;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //création d'un timestamp aléatoire
        $timestamp = mt_rand(1, time());
        $date = new \DateTime("2009-04-10");
        $date->setTimestamp($timestamp);

        for ($i = 0; $i < 20; $i++) {
            $rent = new Rent();
            $rent->setTenantId($this->getReference('user-'.rand(1,3)));
            $rent->setResidenceId($this->getReference('residence-'.rand(1,3)));
            $rent->setInventoryFile('inventory_file-'. $i);
            $rent->setArrivalDate($date);
            $rent->setDepartureDate($date);
            $rent->setTenantComments('tenant_comments-'. $i);
            $rent->setTenantSignature('tenant_signature-'. $i);
            $rent->setTenantValidatedAt('tenant_validated_at-'. $i);
            $rent->setRepresentativeComments('representative_comments-'. $i);
            $rent->setRepresentativeSignature('representative_signature-'. $i);
            $rent->setRepresentativeValidatedAt($date);
            $manager->persist($rent);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ResidenceFixtures::class,
            UserFixtures::class,
        ];
    }
}
