<?php

namespace App\Repository;

use App\Entity\Shootings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Shootings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shootings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shootings[]    findAll()
 * @method Shootings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShootingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Shootings::class);
    }

    // /**
    //  * @return Shootings[] Returns an array of Shootings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shootings
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
