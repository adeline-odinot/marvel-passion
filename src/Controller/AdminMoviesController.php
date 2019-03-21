<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Form\MovieType;
use App\Service\Upload;
use App\Service\Paginator;
use App\Repository\MoviesRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMoviesController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des articles de film
     * 
     * @Route("/admin/movies/{page}", name="admin_movies_index", requirements={"page": "\d+"})
     *
     * @var $page
     * @param Paginator $paginator
     * 
     * @return Response
     */
    public function index($page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Movies::class)
                  ->setPage($page);

        return $this->render('admin/movies/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de créer un article de film
     * 
     * @Route("/admin/movies/createMovie", name="create_movie")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function createMovie(Request $request, ObjectManager $manager, Upload $upload)
    {
        $movie = new Movies();
        
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            if (isset($request->files->get('movie')['image']))
            {
                $fileName = $upload->upload($this->getParameter('movies_directory'), $request->files->get('movie')['image']);

                if (!$fileName)
                {
                    $valid = false;
                    $form->get('image')->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                }
                else
                {
                    $movie->setImage($fileName);
                }
            }
            else
            {
                $valid = false;
                $form->get('image')->addError(new FormError("Veuillez séléctionner une image."));
            }
            
            if ($valid)
            {
                $movie->setUser($this->getUser());
                $movie->setCreationDate(new \DateTime());
                

                $manager->persist($movie);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'article de film <strong>{$movie->getTitle()}</strong> a bien été enregistré !"
                );

                return $this->redirectToRoute('admin_movies_index');
            }
        }
        
        return $this->render('admin/movies/createMovie.html.twig', [
            'formMovie' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un article de film
     * 
     * @Route("/admin/movies/{id}/editMovie", name="edit_movie")
     * 
     * @param Movies $movie
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function editMovie(Movies $movie, Request $request, ObjectManager $manager, Upload $upload)
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            if (isset($request->files->get('movie')['image']))
            {
                $fileName = $upload->upload($this->getParameter('movies_directory'), $request->files->get('movie')['image'], $movie->getImage());

                if(!$fileName)
                {
                    $valid = false;
                    $form->get('image')->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                }
                else
                {
                    $movie->setImage($fileName);
                }
            }
            if($valid)
            {
                $manager->persist($movie);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "Les modifications de l'article de film <strong>{$movie->getTitle()}</strong> ont bien été enregistrées !"
                );
    
                return $this->redirectToRoute('admin_movies_index');
            }

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
     * @param Movies $movie
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteMovie(Movies $movie, ObjectManager $manager)
    {
        $comments = $movie->getComments();
        
        foreach($comments as $comment)
        {
            $manager->remove($comment);
            $manager->flush();
        }

        $manager->remove($movie);
        $manager->flush();

        return new JsonResponse('Suppression réussi');
    }
}