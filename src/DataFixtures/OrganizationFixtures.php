<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrganizationFixtures extends Fixture
{
    public const ORG_REF = 'org-ref-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $organization = (new Organization())
                ->setName($faker->company())
                ->setPresentation($faker->realText(200))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')))
            ;

            $manager->persist($organization);
            $manager->flush();
            $this->addReference(self::ORG_REF . $i, $organization);
        }
    }
}
