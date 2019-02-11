<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use EasySlugger\Slugger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // créer un utilisateur ayant un rôle super admin
        $user = new User();
        $user->setEmail('supadmin@example.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        // encoder le mot de passe
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);

        // créer un utilisateur ayant un rôle admin
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        // encoder le mot de passe
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $manager->persist($user);

        // créer un student
        $student = new Student();
        $student->setFirstname('Toto');
        $student->setLastname('Pop');
        $student->setEmail('toto.pop@example.com');
        $manager->persist($student);

        // créer un autre student
        $student = new Student();
        $student->setFirstname('Titi');
        $student->setLastname('Pop');
        $student->setEmail('titi.pop@example.com');
        $manager->persist($student);

        // créer un générateur de fausses données, localisé pour le français
        $faker = \Faker\Factory::create('fr_FR');

        // créer 10 students
        for ($i = 0; $i < 10; $i++) {
            // générer un prénom et un nom de famille
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;

            // sluggifier le prénom et le nom de famille (enlever les majuscules et les accents)
            // et concaténer avec un nom de domaine de mail généré
            $email = Slugger::slugify($firstname).'.'.Slugger::slugify($lastname).'@'.$faker->safeEmailDomain;

            // créer un student avec les données générées
            $student = new Student();
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setEmail($email);
            $manager->persist($student);
        }

        // sauvegarder le tout en BDD
        $manager->flush();
    }
}
