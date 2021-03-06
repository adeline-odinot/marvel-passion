<?php

namespace App\MarvelPassion\ArticleBundle\Controller\Front;

use App\MarvelPassion\ArticleBundle\Entity\Movies;
use App\MarvelPassion\CommentBundle\Entity\Comments;
use App\MarvelPassion\CommentBundle\Form\CommentType;
use App\Service\Paginator;
use App\MarvelPassion\ArticleBundle\Repository\MoviesRepository;
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
     * @Route("/movies/{page}", name="movies", requirements={"page": "\d+"})
     * 
     * @var $page
     * @param Paginator $paginator
     * 
     * @return Response
     */
    public function index($page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Movies::class)
                  ->setLimit(4)
                  ->setPage($page);

        return $this->render('ArticleBundle/Ressources/views/Front/movies/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet d'afficher un article de film selon l'id
     * 
     * @Route("/movies/show/{id}", name="show_movies")
     * 
     * @param MoviesRepository $repo
     * @var $id
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

        if ($movies === NULL)
        {
            throw $this->createNotFoundException('Le film que vous recherchez n\'existe pas.');
        }

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

        return $this->render('ArticleBundle/Ressources/views/Front/movies/showMovies.html.twig',
            [
                'movies' => $movies,
                'formComment' => $formComment->createView()
            ]
        );
    }
}