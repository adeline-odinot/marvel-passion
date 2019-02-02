<?php

namespace App\Controller;

use App\Entity\Series;
use App\Form\SerieType;
use App\Service\Upload;
use App\Service\Paginator;
use App\Repository\SeriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSeriesController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des articles de série
     * 
     * @Route("/admin/series/{page}", name="admin_series_index", requirements={"page": "\d+"})
     *
     * @param SeriesRepository $repo
     * @var $page
     * @param Paginator $paginator
     * 
     * @return void
     */

    public function index(SeriesRepository $repo, $page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Series::class)
                  ->setPage($page);

        return $this->render('admin/series/index.html.twig', [
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
            if(!$serie->getId())
            {
                $serie->setUser($this->getUser());
                $serie->setCreationDate(new \DateTime());

                $fileName = $upload->upload($this->getParameter('series_directory'), $request->files->get('serie')['image']);

                $serie->setImage($fileName);
            }

            $manager->persist($serie);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article de la série <strong>{$serie->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_series_index');
        }
        
        return $this->render('admin/series/createSerie.html.twig', [
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
     * 
     * @return Response
     */
    public function editSerie(Series $serie, Request $request, ObjectManager $manager,  Upload $upload)
    {   
        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$serie->getId())
            {
                $serie->setCreationDate(new \DateTime());
            }

            $fileName = $upload->upload($this->getParameter('series_directory'), $request->files->get('serie')['image']);

            $serie->setImage($fileName);

            $manager->persist($serie);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article de la série <strong>{$serie->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_series_index');
        }
        
        return $this->render('admin/series/editSerie.html.twig', [
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
        $manager->remove($serie);
        $manager->flush();

        $this->addFlash(
        'success',
        "L'article de film <strong>{$serie->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_series_index");
    }
}
