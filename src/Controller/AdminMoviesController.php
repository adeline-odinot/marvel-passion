<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Form\MovieType;
use App\Repository\MoviesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMoviesController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des articles de film
     * 
     * @Route("/admin/movies", name="admin_movies_index")
     *
     * @param MoviesRepository $repo
     * @return void
     */

    public function index(MoviesRepository $repo)
    {
        return $this->render('admin/movies/index.html.twig', [
            'movies' => $repo->findAll()
        ]);
    }

    /**
     * Permet de créer un article de film
     * 
     * @Route("/admin/movies/createMovie", name="create_movie")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function createMovie(Request $request, ObjectManager $manager)
    {
        $movie = new Movies();
        
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$movie->getId())
            {
                $movie->setUser($this->getUser());
                $movie->setCreationDate(new \DateTime());
            }

            $manager->persist($movie);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article de film <strong>{$movie->getTitle()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_movies_index');
        }
        
        return $this->render('admin/movies/createMovie.html.twig', [
            'formMovie' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un film
     * 
     * @Route("/admin/movies/{id}/editMovie", name="edit_movie")
     * 
     * @param Movies $movie
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function editMovie(Movies $movie, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$movie->getId())
            {
                $movie->setCreationDate(new \DateTime());
            }

            $manager->persist($movie);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'article de film <strong>{$movie->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('admin_movies_index');
        }
        
        return $this->render('admin/movies/editMovie.html.twig', [
            'formMovie' => $form->createView(),
            'movie' => $movie
        ]);
    }

    /**
     * Permet de supprimer un article film
     *
     * @Route("/admin/movies/{id}/deleteMovie", name="delete_movie")
     *
     * @return Response
     * @param Movies $movie
     * @param ObjectManager $manager
     */
    public function deleteMovie(Movies $movie, ObjectManager $manager)
    {
        $manager->remove($movie);
        $manager->flush();

        $this->addFlash(
        'success',
        "L'article de film <strong>{$movie->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_movies_index");
    }
}
