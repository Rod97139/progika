<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        
        $admin1 = new User();
        $admin1->setEmail('admin@gmail.com');
        $admin1->setPassword($this->hasher->hashPassword($admin1, 'admin'));
        $admin1->setRoles(['ROLE_ADMIN']);
        $admin1->setCreatedAt(new \DateTime());
        $admin2 = new User();
        $admin2->setEmail('admin2@gmail.com');        
        $admin2->setPassword($this->hasher->hashPassword($admin2, 'admin'));
        $admin2->setRoles(['ROLE_ADMIN']);
        $admin2->setCreatedAt(new \DateTime());
        $manager->persist($admin1);
        $manager->persist($admin2);

        for ($i=1; $i < 5; $i++) { 
            $user = new User();
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setPassword($this->hasher->hashPassword($user, 'user'));
            $user->setCreatedAt(new \DateTime());
            $owner = new User();
            $owner->setEmail('owner' . $i . '@gmail.com');
            $owner->setPassword($this->hasher->hashPassword($owner, 'owner'));
            $owner->setRoles(['ROLE_OWNER']);
            $owner->setFirstname($faker->firstName);
            $owner->setLastname($faker->lastName);
            $owner->setPhone($faker->phoneNumber);
            $owner->setDisponibility("Du lundi au jeudi de 18H00 à 19h30");
            $owner->setCreatedAt(new \DateTime());
            $manager->persist($user);
            $manager->persist($owner);
        }
        $manager->flush();

    }

    public static function getGroups(): array
      {
        return ['user'];
      }
}
