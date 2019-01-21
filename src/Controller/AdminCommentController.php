<?php

namespace App\Controller;

use App\Entity\Comments;
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
     * @Route("/admin/comments", name="admin_comments_index")
     *
     * @param CommentsRepository $repo
     * @return void
     */

    public function index(CommentsRepository $repo)
    {
        $repo = $this->getDoctrine()->getRepository(Comments::class);

        $comments = $repo->findAll();

        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
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
