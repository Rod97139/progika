<?php

namespace App\DataFixtures;

use App\Entity\Lodging;
use App\Repository\CityRepository;
use App\Repository\CriteriaRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class LodgingFixtures extends Fixture implements DependentFixtureInterface
// class LodgingFixtures extends Fixture
{
    
    public function __construct(
        private CityRepository $cityRepository,
        private UserRepository $userRepository,
        private CriteriaRepository $criteriaRepository
    ) {}
    public function load(ObjectManager $manager): void

    {
        $faker = Factory::create('fr_FR');

        $cities = $this->cityRepository->findAll();
        
        $users = $this->userRepository->findByRole('ROLE_OWNER');

        $pictures = [
            '/uploads/images/gite.jpg',
            '/uploads/images/gite3.jpg',
            '/uploads/images/gite4.jpg',
            '/uploads/images/gite5.jpg'
            ];
        
        $criteria = $this->criteriaRepository->findAll();




        for ($i=0; $i < 40 ; $i++) { 
            $lodging = new Lodging();

            $picturesIndex = array_rand($pictures, 1);
            $cityIndex = array_rand($cities, 1);
            $userIndex = array_rand($users, 1);

            $lodging->setCity($cities[$cityIndex]);
            $lodging->setUser($users[$userIndex]);
        
            $lodging->setName($faker->lexify('Gîte ????'));
            // $lodging->setDescription($faker->text(200));
            $lodging->setDescription('test description');
            $nbrRooms = $faker->numberBetween(2,5); 
            $lodging->setNumberRooms($nbrRooms);
            $lodging->setMaxPeople($nbrRooms + 1);
            $surface = $faker->numberBetween(50,300);
            $lodging->setSurface($surface);
            $lodging->setWeeklyBasePrice($surface * 1.5);
            $lodging->setAdress($faker->streetAddress);
            $lodging->setImage($pictures[$picturesIndex]);
            $lodging->setCreatedAt(new \DateTime());

            $lodging->addCriterion($criteria[1])
                    ->addCriterion($criteria[6])
                    ->addCriterion($criteria[8])
                    ->addCriterion($criteria[9])
                    ->addCriterion($criteria[13])
                    ->addCriterion($criteria[15])
                    ->addCriterion($criteria[17]);

            $manager->persist($lodging);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
    
}
