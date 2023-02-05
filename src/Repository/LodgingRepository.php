<?php

namespace App\Repository;

use App\Entity\Lodging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
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
        ->select('COUNT(l)');
    if($filters['criterion'] != null){
        $query->andWhere('c IN(:crit)')
        ->join('l.criteria', 'c')
        ->setParameter(':crit', array_values($filters['criterion']));
    }
    if ($filters['region'] != null) {
        $query->join('l.city', 'v', 'WITH', 'v.id = l.city')
        ->join('v.departement', 'dpt', 'WITH', 'dpt.code = v.departement')
        ->andWhere('dpt.region = (:region)')
        ->setParameter(':region', $filters['region']);
    }

    return $query->getQuery()->getSingleScalarResult();
}

   public function getPaginatedLodgings($page, $limit, $filters = null): array
   {
        $query = $this->createQueryBuilder('l')
            // ->orderBy('l.created_at', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ;
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
                $query->setParameter(':crit', array_values($filters['criterion']));
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
