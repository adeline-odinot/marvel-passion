<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentType;
use App\Repository\HumorRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HumorController extends AbstractController
{
    /**
     * Permet d'afficher la page d'humour
     * 
     * @Route("/humor", name="humor")
     * 
     * @return Response
     */
    public function index(HumorRepository $repo)
    {

        $humor = $repo->findAll();

        return $this->render('humor/index.html.twig',
            [
                'humor' => $humor,
            ]
        );
    }

    /**
     * Permet d'afficher l'article d'humour grâce à son id
     * 
     * @Route("/humor/{id}", name="show_humor")
     * 
     * @param HumorRepository $repo
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function showHumor(HumorRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $comment = new Comments();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        $humor = $repo->find($id);

        if($formComment->isSubmitted() && $formComment->isValid())
        {
            $comment->setHumor($humor)
                    ->setUser($this->getUser())
                    ->setCreationDate(new \DateTime());
            
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été ajouté."
            );

            return $this->redirectToRoute('show_humor', ['id' => $humor->getId()]);
        }

       
        return $this->render('humor/showHumor.html.twig',
            [
                'humor' => $humor,
                'formComment' => $formComment->createView()
            ]
        );
    }
}
