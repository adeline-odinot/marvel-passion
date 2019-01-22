<?php

namespace App\Controller;

use App\Repository\HumorRepository;
use App\Repository\MoviesRepository;
use App\Repository\SeriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Permet d'afficher la page d'accueil
     * 
     * @Route("/", name="home")
     * 
     * @return Response
     */
    public function index(MoviesRepository $moviesRepo, SeriesRepository $seriesRepo, HumorRepository $humorRepo)
    {
        $movies = $moviesRepo->findMoviesByLimit(2);
        $series = $seriesRepo->findSeriesByLimit(2);
        $humor = $humorRepo->findHumorByLimit(1);

        $slide = array_merge($movies, $series);

        return $this->render('home/index.html.twig',
            [
                'humor' => $humor,
                'slide' => $slide
            ]
        );
    }
}
