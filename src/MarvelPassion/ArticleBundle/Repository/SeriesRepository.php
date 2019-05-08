<?php

namespace App\MarvelPassion\ArticleBundle\Repository;

use App\MarvelPassion\ArticleBundle\Entity\Series;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Series|null find($id, $lockMode = null, $lockVersion = null)
 * @method Series|null findOneBy(array $criteria, array $orderBy = null)
 * @method Series[]    findAll()
 * @method Series[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Series::class);
    }

    public function findSeriesByLimit($limit)
    {
        return $this->createQueryBuilder('s')
                    ->orderBy('s.id', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();

    }
}
