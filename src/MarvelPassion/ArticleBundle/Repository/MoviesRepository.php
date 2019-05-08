<?php

namespace App\MarvelPassion\ArticleBundle\Repository;

use App\MarvelPassion\ArticleBundle\Entity\Movies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movies[]    findAll()
 * @method Movies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoviesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movies::class);
    }

    public function findMoviesByLimit($limit)
    {
        return $this->createQueryBuilder('m')
                    ->orderBy('m.id', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();

    }
}
