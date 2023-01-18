<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Lodging;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LodgingFixtures extends Fixture
{
    

    public function load(ObjectManager $manager): void

    {
        $data =  $data = [
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gite_example.jpg']
       ];

        for ($i=0; $i < count($data); $i++) { 
            $lodging = new Lodging();
            // $lodging->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            // $lodging->setCity($this->getReference(CityFixtures::CITY_REFERENCE));
            $lodging->setName($data[$i][0]);
            $lodging->setDescription($data[$i][1]);
            $lodging->setNumberRooms($data[$i][2]);
            $lodging->setMaxPeople($data[$i][3]);
            $lodging->setSurface($data[$i][4]);
            $lodging->setWeeklyBasePrice($data[$i][5]);
            $lodging->setAdress($data[$i][6]);
            $lodging->setImage($data[$i][7]);
            $lodging->setCreatedAt(new \DateTime());
            $manager->persist($lodging);
        }

        $manager->flush();
    }
    
}
