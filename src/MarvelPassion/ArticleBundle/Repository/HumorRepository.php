<?php

namespace App\MarvelPassion\ArticleBundle\Repository;

use App\MarvelPassion\ArticleBundle\Entity\Humor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Humor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Humor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Humor[]    findAll()
 * @method Humor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Humor::class);
    }

    public function findHumorByLimit($limit)
    {
        return $this->createQueryBuilder('h')
                    ->orderBy('h.id', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }
}
