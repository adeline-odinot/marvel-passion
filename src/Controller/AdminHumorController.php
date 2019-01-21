<?php

namespace App\Controller;

use App\Entity\Humor;
use App\Form\HumorType;
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
     * @Route("/admin/humor", name="admin_humor_index")
     *
     * @param HumorRepository $repo
     * @return void
     */

    public function index(HumorRepository $repo)
    {
        return $this->render('admin/humor/index.html.twig', [
            'humor' => $repo->findAll()
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

    public function createHumor(Request $request, ObjectManager $manager)
    {
        $humor = new Humor();

        $form = $this->createForm(HumorType::class, $humor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
    public function editHumor(Humor $humor, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(HumorType::class, $humor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
