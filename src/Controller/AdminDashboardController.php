<?php

namespace App\Controller;

use App\Service\Stats;
use App\Repository\HumorRepository;
use App\Repository\MoviesRepository;
use App\Repository\SeriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\ShootingsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher le tableau de bord de l'administration
     * 
     * @Route("/admin", name="admin")
     * 
     * @param ObjectManager $manager
     * @param Stats $stats
     * @param ShootingsRepository $shootingsRepo
     * @param MoviesRepository $moviesRepo
     * @param SeriesRepository $seriesRepo
     * @param HumorRepository $humorRepo
     * @param CommentsRepository $commentsRepo
     * 
     * @return Response
     */
    public function index(ObjectManager $manager, Stats $stats, ShootingsRepository $shootingsRepo, MoviesRepository $moviesRepo, SeriesRepository $seriesRepo, HumorRepository $humorRepo, CommentsRepository $commentsRepo)
    {
        $getStats = $stats->getStats();

        $shootings = $shootingsRepo->findShootingsByLimit(5);
        $movies = $moviesRepo->findMoviesByLimit(5);
        $series = $seriesRepo->findSeriesByLimit(5);
        $humor = $humorRepo->findHumorByLimit(5);
        $comments = $commentsRepo->findCommentsByLimit(5);

        return $this->render('admin/dashboard/index.html.twig',
        [
            'shootings' => $shootings,
            'movies' => $movies,
            'series' => $series,
            'humor' => $humor,
            'comments' => $comments,
            'stats' => $getStats
        ]);
    }
}