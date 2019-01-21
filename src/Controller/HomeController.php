<?php

namespace App\Controller;

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
    public function index(MoviesRepository $moviesRepo, SeriesRepository $seriesRepo)
    {
        $movies = $moviesRepo->findAll();
        $series = $seriesRepo->findAll();

        return $this->render('home/index.html.twig',
            [
                'movies' => $movies,
                'series' => $series,
            ]
        );
    }
}
