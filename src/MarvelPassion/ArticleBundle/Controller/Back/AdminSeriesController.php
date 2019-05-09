<?php

namespace App\MarvelPassion\ArticleBundle\Controller\Back;

use App\MarvelPassion\ArticleBundle\Entity\Series;
use App\MarvelPassion\ArticleBundle\Form\SerieType;
use App\Service\Upload;
use App\Service\Paginator;
use App\MarvelPassion\ArticleBundle\Repository\SeriesRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSeriesController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des articles de série
     * 
     * @Route("/admin/series/{page}", name="admin_series_index", requirements={"page": "\d+"})
     *
     * @var $page
     * @param Paginator $paginator
     * 
     * @return Response
     */
    public function index($page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Series::class)
                  ->setPage($page);

        return $this->render('ArticleBundle/Ressources/views/Back/series/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de créer un article de série
     * 
     * @Route("/admin/series/createSerie", name="create_serie")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function createSerie(Request $request, ObjectManager $manager, Upload $upload)
    {
        $serie = new Series();
        
        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $fileName = $upload->upload($this->getParameter('series_directory'), $request->files->get('serie')['image'], $form->get('image'));
            
            if ($fileName)
            {
                $serie->setImage($fileName);
                $serie->setUser($this->getUser());
                $serie->setCreationDate(new \DateTime());
                
    
                $manager->persist($serie);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'article de la série <strong>{$serie->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_series_index');
            }
            else
            {
                $form->get('image')->addError(new FormError("Veuillez séléctionner une image."));
            }
        }
        
        return $this->render('ArticleBundle/Ressources/views/Back/series/createSerie.html.twig', [
            'formSerie' => $form->createView(),
        ]);
    }

    /**
     * Permet de modifier un article de série
     * 
     * @Route("/admin/series/{id}/editSerie", name="edit_serie")
     * 
     * @param Series $serie
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function editSerie(Series $serie, Request $request, ObjectManager $manager,  Upload $upload)
    {   
        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $fileName = $upload->upload($this->getParameter('series_directory'), $request->files->get('serie')['image'], $form->get('image') ,$serie->getImage());
                
            if($fileName)
            {
                $serie->setImage($fileName);
            }

            $manager->persist($serie);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'article de la série <strong>{$serie->getTitle()}</strong> ont bien été enregistrée !"
            );
            
            return $this->redirectToRoute('admin_series_index'); 
        }
        
        return $this->render('ArticleBundle/Ressources/views/Back/series/editSerie.html.twig', [
            'formSerie' => $form->createView(),
            'serie' => $serie
        ]);
    }

    /**
     * Permet de supprimer un article de série
     *
     * @Route("/admin/series/{id}/deleteSerie", name="delete_serie")
     * 
     * @param Series $serie
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteSerie(Series $serie, ObjectManager $manager)
    {
        $comments = $serie->getComments();
        
        foreach($comments as $comment)
        {
            $manager->remove($comment);
            $manager->flush();
        }

        $manager->remove($serie);
        $manager->flush();

        return new JsonResponse('Suppression réussi');
    }
}
