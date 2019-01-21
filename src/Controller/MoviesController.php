<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentType;
use App\Repository\MoviesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoviesController extends AbstractController
{
    /**
     * Permet d'afficher la page des films
     * 
     * @Route("/movies", name="movies")
     * 
     * @return Response
     */
    public function index(MoviesRepository $repo)
    {

        $movies = $repo->findAll();

        return $this->render('movies/index.html.twig',
            [
                'movies' => $movies,
            ]
        );
    }

    /**
     * Permet d'afficher un article de film selon l'id
     * 
     * @Route("/movies/{id}", name="show_movies")
     * 
     * @param MoviesRepository $repo
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function showMovies(MoviesRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $comment = new Comments();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        $movies = $repo->find($id);

        if($formComment->isSubmitted() && $formComment->isValid())
        {
            $comment->setMovie($movies)
                    ->setUser($this->getUser())
                    ->setCreationDate(new \DateTime());
            
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été ajouté."
            );

            return $this->redirectToRoute('show_movies', ['id' => $movies->getId()]);
        }

        return $this->render('movies/showMovies.html.twig',
            [
                'movies' => $movies,
                'formComment' => $formComment->createView()
            ]
        );
    }
}