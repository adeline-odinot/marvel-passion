<?php

namespace App\MarvelPassion\HomeBundle\Controller\Front;

use App\MarvelPassion\ArticleBundle\Repository\HumorRepository;
use App\MarvelPassion\ArticleBundle\Repository\MoviesRepository;
use App\MarvelPassion\ArticleBundle\Repository\SeriesRepository;
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
     * @param MoviesRepository $moviesRepo
     * @param SeriesRepository $seriesRepo
     * @param HumorRepository $humorRepo
     * 
     * @return Response
     */
    public function index(MoviesRepository $moviesRepo, SeriesRepository $seriesRepo, HumorRepository $humorRepo)
    {
        $movies = $moviesRepo->findMoviesByLimit(2);
        $series = $seriesRepo->findSeriesByLimit(2);
        $humor = $humorRepo->findHumorByLimit(2);

        $slide = array_merge($movies, $series);

        return $this->render('HomeBundle/Ressources/views/Front/index.html.twig',
            [
                'humor' => $humor,
                'slide' => $slide
            ]
        );
    }
}