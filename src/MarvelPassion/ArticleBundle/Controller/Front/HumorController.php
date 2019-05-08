<?php

namespace App\MarvelPassion\ArticleBundle\Controller\Front;

use App\MarvelPassion\ArticleBundle\Entity\Humor;
use App\MarvelPassion\CommentBundle\Entity\Comments;
use App\MarvelPassion\CommentBundle\Form\CommentType;
use App\Service\Paginator;
use App\MarvelPassion\ArticleBundle\Repository\HumorRepository;
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
     * @Route("/humor/{page}", name="humor", requirements={"page": "\d+"})
     * 
     * @var $page
     * @param Paginator $paginator
     * 
     * @return Response
     */
    public function index($page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Humor::class)
                  ->setPage($page);

        return $this->render('ArticleBundle/Ressources/views/Front/humor/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet d'afficher l'article d'humour grâce à son id
     * 
     * @Route("/humor/show/{id}", name="show_humor")
     * 
     * @param HumorRepository $repo
     * @var $id
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

        if ($humor === NULL)
        {
            throw $this->createNotFoundException('L\'humour que vous recherchez n\'existe pas.');
        }

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

       
        return $this->render('ArticleBundle/Ressources/views/Front/humor/showHumor.html.twig',
            [
                'humor' => $humor,
                'formComment' => $formComment->createView()
            ]
        );
    }
}