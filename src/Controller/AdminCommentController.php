<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Service\Paginator;
use App\Form\AdminCommentType;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Affichage des commentaires dans l'administration
     * 
     * @Route("/admin/comments/{page}", name="admin_comments_index", requirements={"page": "\d+"})
     *
     * @param CommentsRepository $repo
     * @var $page
     * @param Paginator $paginator
     * @return void
     */

    public function index(CommentsRepository $repo, $page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Comments::class)
                  ->setPage($page);

        return $this->render('admin/comments/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de modifier un commentaire
     * 
     * @Route("/admin/comments/{id}/editComment", name="edit_comments")
     * 
     * @param Comments $comments
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function editComment(Comments $comments, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comments);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comments);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications du commentaire ont bien été enregistrées !"
            );

            return $this->redirectToRoute('admin_comments_index');
        }

        return $this->render('admin/comments/editComment.html.twig', [
            'formComment' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Permet de supprimer un commentaire
    *
    * @Route("/admin/comments/{id}/deleteComment", name="delete_comments")
    *
    * @return Response
    * @param Comments $comments
    * @param ObjectManager $manager
    */
    public function deleteComment(Comments $comments, ObjectManager $manager)
    {
        $manager->remove($comments);
        $manager->flush();

        $this->addFlash(
        'success',
        "Le commentaire a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_comments_index");
    }
}
