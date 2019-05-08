<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Stats
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $shootings = $this->getShootingsCount();
        $movies = $this->getMoviesCount();
        $series = $this->getSeriesCount();
        $humor = $this->getHumorCount();
        $comments = $this->getCommentsCount();
        $users = $this->getUsersCount();

        return compact('shootings','movies','series','humor','comments','users');
    }

    public function getShootingsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(s) FROM App\MarvelPassion\ShootingBundle\Entity\Shootings s')->getSingleScalarResult();
    }

    public function getMoviesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(m) FROM App\MarvelPassion\ArticleBundle\Entity\Movies m')->getSingleScalarResult();
    }

    public function getSeriesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(s) FROM App\MarvelPassion\ArticleBundle\Entity\Series s')->getSingleScalarResult();
    }

    public function getHumorCount()
    {
        return $this->manager->createQuery('SELECT COUNT(h) FROM App\MarvelPassion\ArticleBundle\Entity\Humor h')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\MarvelPassion\CommentBundle\Entity\Comments c')->getSingleScalarResult();
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\MarvelPassion\UserBundle\Entity\Users u')->getSingleScalarResult();
    }
}