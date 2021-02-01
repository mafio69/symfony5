<?php

namespace App\Repository;

use App\Entity\RealEstates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RealEstates|null find($id, $lockMode = null, $lockVersion = null)
 * @method RealEstates|null findOneBy(array $criteria, array $orderBy = null)
 * @method RealEstates[]    findAll()
 * @method RealEstates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealEstatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealEstates::class);
    }

    // /**
    //  * @return RealEstates[] Returns an array of RealEstates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RealEstates
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
