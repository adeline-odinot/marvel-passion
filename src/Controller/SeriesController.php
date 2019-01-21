<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentType;
use App\Repository\SeriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeriesController extends AbstractController
{
    /**
     * Permet d'afficher la page des séries
     * 
     * @Route("/series", name="series")
     * 
     * @return Response
     */
    public function index(SeriesRepository $repo)
    {
        $series = $repo->findAll();

        return $this->render('series/index.html.twig',
            [
                'series' => $series,
            ]
        );
    }

    /**
     * Permet de d'afficher un article de série selon l'id
     * 
     * @Route("/series/{id}", name="show_series")
     * 
     * @param SeriesRepository $repo
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function showSeries(SeriesRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $comment = new Comments();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        $series = $repo->find($id);

        if($formComment->isSubmitted() && $formComment->isValid())
        {
            $comment->setSeries($series)
                    ->setUser($this->getUser())
                    ->setCreationDate(new \DateTime());
            
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été ajouté."
            );

            return $this->redirectToRoute('show_series', ['id' => $series->getId()]);
        }

        return $this->render('series/showSeries.html.twig',
            [
                'series' => $series,
                'formComment' => $formComment->createView()
            ]
        );
    }
}
