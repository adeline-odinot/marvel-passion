<?php

namespace App\MarvelPassion\ShootingBundle\Repository;

use App\MarvelPassion\ShootingBundle\Entity\Shootings;
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

    public function findShootingsByLimit($limit)
    {
        return $this->createQueryBuilder('s')
                    ->orderBy('s.id', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }
}
