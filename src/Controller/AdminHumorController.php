<?php

namespace App\Controller;

use App\Entity\Humor;
use App\Form\HumorType;
use App\Service\Upload;
use App\Service\Paginator;
use App\Repository\HumorRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminHumorController extends AbstractController
{
    /**
     * Affichage de la page d'administration des articles d'humour
     * 
     * @Route("/admin/humor/{page}", name="admin_humor_index", requirements={"page": "\d+"})
     *
     * @param HumorRepository $repo
     * @var $page
     * @param Paginator $paginator
     * @return void
     */

    public function index(HumorRepository $repo, $page = 1, Paginator $paginator)
    {   
        $paginator->setEntityClass(Humor::class)
                  ->setPage($page);

        return $this->render('admin/humor/index.html.twig', [
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
            $fileName = $upload->upload($this->getParameter('humor_directory'), $request->files->get('humor')['image']);

            $humor->setImage($fileName);

            $humor->setUser($this->getUser());
            $humor->setCreationDate(new \DateTime());

            $manager->persist($humor);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article de l'image d'humour <strong>{$humor->getTitle()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_humor_index');
        }
        
        return $this->render('admin/humor/createHumor.html.twig', [
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
     * 
     * @return Response
     */
    public function editHumor(Humor $humor, Request $request, ObjectManager $manager, Upload $upload)
    {
        $form = $this->createForm(HumorType::class, $humor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $fileName = $upload->upload($this->getParameter('humor_directory'), $request->files->get('humor')['image']);

            $humor->setImage($fileName);

            $manager->persist($humor);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'article de l'image d'humour <strong>{$humor->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('admin_humor_index');
        }

        return $this->render('admin/humor/editHumor.html.twig', [
            'formHumor' => $form->createView(),
            'humor' => $humor
        ]);
    }

    /**
     * Permet de supprimer un article d'humour
    *
    * @Route("/admin/humor/{id}/deleteHumor", name="delete_humor")
    *
    * @return Response
    * @param Humor $humor
    * @param ObjectManager $manager
    */
    public function deleteHumor(Humor $humor, ObjectManager $manager)
    {
        $manager->remove($humor);
        $manager->flush();

        $this->addFlash(
        'success',
        "L'article de l'image d'humour <strong>{$humor->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("admin_humor_index");
    }
}
