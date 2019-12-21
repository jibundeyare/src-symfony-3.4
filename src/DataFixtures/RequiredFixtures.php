<?php

namespace App\DataFixtures;

use App\Entity\DeliciousJellybean;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RequiredFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $jb = new DeliciousJellybean();
        $jb
            ->setColor('pink')
            ->setDiameter(3.14)
            ->setFlavour('strawberry');

        $manager->persist($jb);

        $colors = [
            'pink',
            'blue',
        ];

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 99; $i++) {
            $jb = new DeliciousJellybean();
            $jb
                ->setColor($colors[random_int(0, 1)])
                ->setDiameter(random_int(300, 500) / 100)
                ->setFlavour($faker->sentence(3, true));

            $manager->persist($jb);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['required'];
    }
}
