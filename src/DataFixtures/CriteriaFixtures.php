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
            ['Location de linge', 'Services'],
            ['Accès internet', 'Services'],
            ['Massage', 'Services'],
            ['Location de voiture', 'Services'],
       ];

        for ($i=0; $i < count($data); $i++) { 
            $criteria = new Criteria();
            $criteria->setName($data[$i][0]);
            $criteria->setType($data[$i][1]);
            $manager->persist($criteria);
            // name type
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
