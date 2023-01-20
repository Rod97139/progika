<?php

namespace App\DataFixtures;

use App\Entity\Criteria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CriteriaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $data =  $data = [
            ['Lave-vaisselle', 'Équipements intérieurs'],
            ['Lave-linge', 'Équipements intérieurs'],
            ['Climatisation', 'Équipements intérieurs'],
            ['Télévision', 'Équipements intérieurs'],
            ['Playstation', 'Équipements intérieurs'],
            ['Salle de sport', 'Équipements intérieurs'],
            ['Bibliothèque', 'Équipements intérieurs'],
            ['Terrasse', 'Équipements extérieurs'],
            ['Barbecue', 'Équipements extérieurs'],
            ['Piscine privée', 'Équipements extérieurs'],
            ['Piscine partagée', 'Équipements extérieurs'],
            ['Tennis', 'Équipements extérieurs'],
            ['Ping-pong', 'Équipements extérieurs'],
            ['Basket', 'Équipements extérieurs'],
            ['Location de linge', 'Services', 20],
            ['Accès internet', 'Services', 10],
            ['Massage en fin de séjour', 'Services', 50],
            ['Location de voiture', 'Services', 50],
            ['Animaux acceptés', 'Privilèges', 30]
       ];

        for ($i=0; $i < count($data); $i++) { 
            $criteria = new Criteria();
            $criteria->setName($data[$i][0]);
            $criteria->setType($data[$i][1]);
            if (isset($data[$i][2])){
                $criteria->setPrice($data[$i][2]);
            }

            $manager->persist($criteria);
        }

        $manager->flush();
    }
}
