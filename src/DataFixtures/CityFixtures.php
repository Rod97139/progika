<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    // public const CITY_REFERENCE = 'city';

    public function load(ObjectManager $manager): void
    {
        // $city = new City();
        //     $city->setName('FakeCity');
        //     $city->setSlug('fakecity');
        //     $city->setGpsLat(5);
        //     $city->setGpsLng(3);
        //     $manager->persist($city);
        //     $manager->flush();


        // $this->addReference(self::CITY_REFERENCE, $city);
    }
}
