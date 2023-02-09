<?php

namespace App\Repository;

use App\Entity\Lodging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lodging>
 *
 * @method Lodging|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lodging|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lodging[]    findAll()
 * @method Lodging[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LodgingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Lodging::class);
    }

    public function save(Lodging $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lodging $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Lodging[] Returns an array of Lodging objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findByCriteria($filters = null): array
//    {
//        $query = $this->createQueryBuilder('l');
       
//         if ($filters['criterion'] != null) {
//             $query->andWhere('c IN(:crit)')
//             ->join('l.criteria', 'c')
//             ->setParameter(':crit', array_values($filters['criterion']));
//         }

//         $query->orderBy('l.created_at');

//        return $query->getQuery()->getResult();
//    }

   public function getTotalLodgings($filters = null){
    $query = $this->createQueryBuilder('l')
        ;
        if ($filters['price']['high'] != null || $filters['price']['low'] != null){
            if ($filters['price']['high'] == null) {
                $filters['price']['high']  = $this->maxPrice();
            }elseif($filters['price']['low'] == null){
                $filters['price']['low'] = $this->minPrice();
            }
            $query->andWhere('l.weekly_base_price BETWEEN :low AND :high')
                ->setParameter(':low', $filters['price']['low']);
            $query->setParameter(':high', $filters['price']['high']);
        }
        if ($filters['rooms'] != null) {
            $query->andWhere('l.number_rooms = (:rooms)');
            $query->setParameter(':rooms', $filters['rooms']);
        }
        if($filters['criterion'] != null){
            $query->andWhere('c IN(:crit)')
            ->join('l.criteria', 'c')
            ->setParameter(':crit', array_values($filters['criterion']))
            ->groupBy('l.id')
            ->andHaving('COUNT(l.id) = :count')
            ->setParameter(':count', count(array_values($filters['criterion'])))
            ;
        }
        if ($filters['region'] != null) {
            $query->join('l.city', 'v', 'WITH', 'v.id = l.city')
            ->join('v.departement', 'dpt', 'WITH', 'dpt.code = v.departement')
            ->andWhere('dpt.region = (:region)')
            ->setParameter(':region', $filters['region']);
        }
        if ($filters['city']['name'] != null) {
            $query->join('l.city', 'v', 'WITH', 'v.id = l.city');

            if ($filters['city']['zone'] == null){
            $query->andWhere('v.name = (:name)')
            ->setParameter(':name', $filters['city']['name'])
            // ->andWhere('v.zip_code = (:zip)')
            // ->setParameter(':zip', $filters['city']['zip_code'])
            ;
            } else {
                $query->addSelect('( 6371.4 * acos(cos(radians(' . $filters['city']['GpsLat'] . '))
                                    * cos( radians( v.gps_lat ) )
                                    * cos( radians( v.gps_lng )
                                    - radians(' . $filters['city']['GpsLng'] . ') )
                                    + sin( radians(' . $filters['city']['GpsLat'] . ') )
                                    * sin( radians( v.gps_lat ) ) ) ) as HIDDEN distance');
                                    
                        $query->andHaving('distance <= :zone')
                        ->setParameter(':zone', $filters['city']['zone'])
                    
                    ;
                }

        }

    return $query->getQuery()->getScalarResult();
}

    public function maxPrice()
    {
        $query = $this->em->createQuery("SELECT MAX(l.weekly_base_price) FROM App\Entity\Lodging l");
        return $query->getSingleScalarResult();
    }
    public function minPrice()
    {
        $query = $this->em->createQuery("SELECT MIN(l.weekly_base_price) FROM App\Entity\Lodging l");
        return $query->getSingleScalarResult();
    }

   public function getPaginatedLodgings($page, $limit, $filters = null): array
   {
        $query = $this->createQueryBuilder('l')
            // ->orderBy('l.created_at', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ;
            if ($filters['price']['high'] != null || $filters['price']['low'] != null ){
                if ($filters['price']['high'] == null) {
                    $filters['price']['high']  = $this->maxPrice();
                }elseif($filters['price']['low'] == null){
                    $filters['price']['low'] = $this->minPrice();
                }

                $query->andWhere('l.weekly_base_price BETWEEN :low AND :high')
                    ->setParameter(':low', $filters['price']['low']);
                $query->setParameter(':high', $filters['price']['high']);
            }
            if ($filters['rooms'] != null) {
                $query->andWhere('l.number_rooms = (:rooms)');
                $query->setParameter(':rooms', $filters['rooms']);
            }
            if ($filters['region'] != null) {
                $query
                ->andWhere('dpt.region = (:region)')
                ->join('l.city', 'v', 'WITH', 'v.id = l.city')
                ->join('v.departement', 'dpt', 'WITH', 'dpt.code = v.departement');
                $query->setParameter(':region', $filters['region']);
            }
            if ($filters['criterion'] != null) {
                $query->andWhere('c IN(:crit)')
                ->join('l.criteria', 'c');
                $query->setParameter(':crit', array_values($filters['criterion']))
                ->groupBy('l.id')
                ->andHaving('COUNT(l.id) = :count')
                ->setParameter(':count', count(array_values($filters['criterion'])))
                ;
            }
            if ($filters['city']['name'] !== null) {
                
                $query->join('l.city', 'v', 'WITH', 'v.id = l.city');

                if ($filters['city']['zone'] === null){
                $query->andWhere('v.name = (:name)')
                ->setParameter(':name', $filters['city']['name'])
                // ->andWhere('v.zip_code = (:zip)')
                // ->setParameter(':zip', $filters['city']['zip_code'])
                ;
                } else {
                    $query->addSelect('( 6371.4 * acos(cos(radians(' . $filters['city']['GpsLat'] . '))
                                        * cos( radians( v.gps_lat ) )
                                        * cos( radians( v.gps_lng )
                                        - radians(' . $filters['city']['GpsLng'] . ') )
                                        + sin( radians(' . $filters['city']['GpsLat'] . ') )
                                        * sin( radians( v.gps_lat ) ) ) ) as HIDDEN distance');
                                        
                            $query->andHaving('distance <= :zone')
                            ->setParameter(':zone', $filters['city']['zone'])
                        
                        ;
                    }

            }
        return $query->getQuery()->getResult();
   }

   public function getMappedLodgings($filters = null): array
   {
        $query = $this->createQueryBuilder('l')
            ;
            if ($filters['price']['high'] != null || $filters['price']['low'] != null ){
                if ($filters['price']['high'] == null) {
                    $filters['price']['high']  = $this->maxPrice();
                }elseif($filters['price']['low'] == null){
                    $filters['price']['low'] = $this->minPrice();
                }

                $query->andWhere('l.weekly_base_price BETWEEN :low AND :high')
                    ->setParameter(':low', $filters['price']['low']);
                $query->setParameter(':high', $filters['price']['high']);
            }
            if ($filters['rooms'] != null) {
                $query->andWhere('l.number_rooms = (:rooms)');
                $query->setParameter(':rooms', $filters['rooms']);
            }
            if ($filters['region'] != null) {
                $query
                ->andWhere('dpt.region = (:region)')
                ->join('l.city', 'v', 'WITH', 'v.id = l.city')
                ->join('v.departement', 'dpt', 'WITH', 'dpt.code = v.departement');
                $query->setParameter(':region', $filters['region']);
            }
            if ($filters['criterion'] != null) {
                $query->andWhere('c IN(:crit)')
                ->join('l.criteria', 'c');
                $query->setParameter(':crit', array_values($filters['criterion']))
                ->groupBy('l.id')
                ->andHaving('COUNT(l.id) = :count')
                ->setParameter(':count', count(array_values($filters['criterion'])))
                ;
            }
            if ($filters['city']['name'] !== null) {
                $query->join('l.city', 'v', 'WITH', 'v.id = l.city');

                if ($filters['city']['zone'] == null){
                $query->andWhere('v.name = (:name)')
                ->setParameter(':name', $filters['city']['name'])
                // ->andWhere('v.zip_code = (:zip)')
                // ->setParameter(':zip', $filters['city']['zip_code'])
                ;
                } else {
                    $query->addSelect('( 6371.4 * acos(cos(radians(' . $filters['city']['GpsLat'] . '))
                                        * cos( radians( v.gps_lat ) )
                                        * cos( radians( v.gps_lng )
                                        - radians(' . $filters['city']['GpsLng'] . ') )
                                        + sin( radians(' . $filters['city']['GpsLat'] . ') )
                                        * sin( radians( v.gps_lat ) ) ) ) as HIDDEN distance');
                                        
                            $query->andHaving('distance <= :zone')
                            ->setParameter(':zone', $filters['city']['zone'])
                        ;
                    }
            }
        return $query->getQuery()->getResult();
   }

//    public function findOneBySomeField($value): ?Lodging
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
