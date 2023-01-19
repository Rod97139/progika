<?php

namespace App\DataFixtures;

use App\Entity\Lodging;
use App\Repository\CityRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

// class LodgingFixtures extends Fixture implements DependentFixtureInterface
class LodgingFixtures extends Fixture
{
    
    public function __construct(
        private CityRepository $cityRepository,
        private UserRepository $userRepository
    ) {}
    public function load(ObjectManager $manager): void

    {

        $cities = $this->cityRepository->findAll();
        
        $users = $this->userRepository->findByRole('ROLE_OWNER');

        $data =  $data = [
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg'],
            ['Gîte de base', 'Description du gîte en question', 4, 5, 100, 100, '5 rue de la paix', '/uploads/images/gitetest-63c7a74d73062.jpg']
       ];



        for ($i=0; $i < count($data); $i++) { 
            $lodging = new Lodging();

            $cityIndex = array_rand($cities, 1);
            $userIndex = array_rand($users, 1);

            $lodging->setCity($cities[$cityIndex]);
            $lodging->setUser($users[$userIndex]);
        
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

    // public function getDependencies()
    // {
    //     return [
    //         UserFixtures::class,
    //     ];
    // }
    
}
