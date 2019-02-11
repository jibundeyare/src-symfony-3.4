<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $s = new Student();
        $s->setFirstname('Toto');
        $s->setLastname('Pop');
        $s->setEmail('toto.pop@example.com');
        $manager->persist($s);

        $s = new Student();
        $s->setFirstname('Titi');
        $s->setLastname('Pop');
        $s->setEmail('titi.pop@example.com');
        $manager->persist($s);

        $manager->flush();
    }
}
