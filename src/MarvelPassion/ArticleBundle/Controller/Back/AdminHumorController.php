<?php

namespace App\MarvelPassion\ArticleBundle\Controller\Back;

use App\MarvelPassion\ArticleBundle\Entity\Humor;
use App\MarvelPassion\ArticleBundle\Form\HumorType;
use App\Service\Upload;
use App\Service\Paginator;
use App\MarvelPassion\ArticleBundle\Repository\HumorRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminHumorController extends AbstractController
{
    /**
     * Affichage de la page d'administration des articles d'humour
     * 
     * @Route("/admin/humor/{page}", name="admin_humor_index", requirements={"page": "\d+"})
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

        return $this->render('ArticleBundle/Ressources/views/Back/humor/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * Permet de créer un article d'humour
     * 
     * @Route("/admin/humor/createHumor", name="create_humor")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function createHumor(Request $request, ObjectManager $manager, Upload $upload)
    {
        $humor = new Humor();

        $form = $this->createForm(HumorType::class, $humor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $fileName = $upload->upload($this->getParameter('humor_directory'), $request->files->get('humor')['image'], $form->get('image'));
                
            if($fileName)
            {
                $humor->setImage($fileName);
                $humor->setUser($this->getUser());
                $humor->setCreationDate(new \DateTime());
    
                $manager->persist($humor);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "L'article d'humour <strong>{$humor->getTitle()}</strong> a bien été enregistré !"
                );
    
                return $this->redirectToRoute('admin_humor_index');
            }
            else
            {
                $form->get('image')->addError(new FormError("Veuillez séléctionner une image."));
            }
        }
        
        return $this->render('ArticleBundle/Ressources/views/Back/humor/createHumor.html.twig', [
            'formHumor' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un article d'humour
     * 
     * @Route("/admin/humor/{id}/editHumor", name="edit_humor")
     * 
     * @param Humor $humor
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function editHumor(Humor $humor, Request $request, ObjectManager $manager, Upload $upload)
    {
        $form = $this->createForm(HumorType::class, $humor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $fileName = $upload->upload($this->getParameter('humor_directory'), $request->files->get('humor')['image'], $form->get('image') ,$humor->getImage());

            if($fileName)
            {
                $humor->setImage($fileName);
            }

            $manager->persist($humor);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'article d'humour <strong>{$humor->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('admin_humor_index');
        }

        return $this->render('ArticleBundle/Ressources/views/Back/humor/editHumor.html.twig', [
            'formHumor' => $form->createView(),
            'humor' => $humor
        ]);
    }

    /**
     * Permet de supprimer un article d'humour
     *
     * @Route("/admin/humor/{id}/deleteHumor", name="delete_humor")
     *
     * @param Humor $humor
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteHumor(Humor $humor, ObjectManager $manager)
    {
        $comments = $humor->getComments();
        
        foreach($comments as $comment)
        {
            $manager->remove($comment);
            $manager->flush();
        }

        $manager->remove($humor);
        $manager->flush();

        return new JsonResponse('Suppression réussi');
    }
}