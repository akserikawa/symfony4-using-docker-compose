<?php

namespace App\Repository;

use App\Entity\Bicycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bicycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bicycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bicycle[]    findAll()
 * @method Bicycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BicycleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bicycle::class);
    }

    // /**
    //  * @return Bicycle[] Returns an array of Bicycle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bicycle
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
