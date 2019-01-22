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
        $movies = $this->getMoviesCount();
        $series = $this->getSeriesCount();
        $humor = $this->getHumorCount();
        $comments = $this->getCommentsCount();
        $users = $this->getUsersCount();

        return compact('movies','series','humor','comments','users');
    }

    public function getMoviesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(m) FROM App\Entity\Movies m')->getSingleScalarResult();
    }

    public function getSeriesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(s) FROM App\Entity\Series s')->getSingleScalarResult();
    }

    public function getHumorCount()
    {
        return $this->manager->createQuery('SELECT COUNT(h) FROM App\Entity\Humor h')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comments c')->getSingleScalarResult();
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Users u')->getSingleScalarResult();
    }
}