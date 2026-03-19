<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ConferenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $start = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', '+1 year')->setTime('00', '00', '00'));
            $end = $start->modify('+ ' . $faker->numberBetween(1, 5) . ' days')->setTime('18', '00', '00');

            $conference = (new Conference())
                ->setName($faker->realText(30))
                ->setDescription($faker->realText(500))
                ->setAccessible($faker->boolean())
                ->setStartAt($start)
                ->setEndAt($end)
                ->setCreatedBy($this->getReference(UserFixtures::USER_REF.$faker->randomElement(['admin', 'organizer', 'user']), User::class))
            ;

            for ($j = 0; $j < $faker->numberBetween(0, 3); $j++) {
                $conference->addOrganization($this->getReference(
                        OrganizationFixtures::ORG_REF . $faker->numberBetween(0, 4),
                        Organization::class
                ));
            }

            $manager->persist($conference);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrganizationFixtures::class,
            UserFixtures::class,
        ];
    }
}
