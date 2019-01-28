<?php

namespace App\Controller;

use App\Service\Stats;
use App\Repository\HumorRepository;
use App\Repository\MoviesRepository;
use App\Repository\SeriesRepository;
use App\Repository\CommentsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher l'administration
     * 
     * @Route("/admin", name="admin")
     * 
     * @return Response
     */
    public function index(ObjectManager $manager, Stats $stats, MoviesRepository $moviesRepo, SeriesRepository $seriesRepo, HumorRepository $humorRepo, CommentsRepository $commentsRepo)
    {
        $getStats = $stats->getStats();

        $movies = $moviesRepo->findMoviesByLimit(5);
        $series = $seriesRepo->findSeriesByLimit(5);
        $humor = $humorRepo->findHumorByLimit(5);
        $comments = $commentsRepo->findCommentsByLimit(5);

        return $this->render('admin/dashboard/index.html.twig',
        [
            'movies' => $movies,
            'series' => $series,
            'humor' => $humor,
            'comments' => $comments,
            'stats' => $getStats
            
        ]);
    }
}
