<?php

namespace App\Controller;

use App\Service\Upload;
use App\Entity\Shootings;
use App\Form\ShootingType;
use App\Service\Paginator;
use Symfony\Component\Form\FormError;
use App\Repository\ShootingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminShootingsController extends AbstractController
{
    /**
     * Permet d'afficher la page d'administration des tournages
     * 
     * @Route("/admin/shootings/{page}", name="admin_shootings_index", requirements={"page": "\d+"})
     *
     * @param ShootingsRepository $repo
     * @var $page
     * @param Paginator $paginator
     * 
     * @return void
     */
    public function index(ShootingsRepository $repo, $page = 1, Paginator $paginator)
    {
        $paginator->setEntityClass(Shootings::class)
                  ->setPage($page);

        return $this->render('admin/shootings/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet de créer un lieu de tournage
     * 
     * @Route("/admin/shootings/createShooting", name="create_shooting")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function createShooting(Request $request, ObjectManager $manager, Upload $upload)
    {
        $shooting = new Shootings();
        
        $form = $this->createForm(ShootingType::class, $shooting);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            if ($request->get('shooting')['movie'] === '' && $request->get('shooting')['serie'] === '')
            {
                $valid = false;
                $form->get('movie')->addError(new FormError("Vous devez séléctionner une relation entre un film ou une série."));
                $form->get('serie')->addError(new FormError("Vous devez séléctionner une relation entre un film ou une série."));
            }
            elseif ($request->get('shooting')['movie'] !== '' && $request->get('shooting')['serie'] !== '')
            {
                $valid = false;
                $form->get('movie')->addError(new FormError("Vous ne pouvez pas séléctionner une relation entre un film et une série à la fois."));
                $form->get('serie')->addError(new FormError("Vous ne pouvez pas séléctionner une relation entre un film et une série à la fois."));
            }

            if (isset($request->files->get('shooting')['image']))
            {
                $fileName = $upload->upload($this->getParameter('shootings_directory'), $request->files->get('shooting')['image']);

                if (!$fileName)
                {
                    $valid = false;
                    $form->get('image')->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                }
                else
                {
                    $shooting->setImage($fileName);
                }
            }
            else
            {
                $valid = false;
                $form->get('image')->addError(new FormError("Veuillez séléctionner une image."));
            }
            
            if ($valid)
            {
                $manager->persist($shooting);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "Le lieu de tournage <strong>{$shooting->getTitle()}</strong> a bien été enregistrée !"
                );
    
                return $this->redirectToRoute('admin_shootings_index');
            }
        }
        return $this->render('admin/shootings/createShooting.html.twig', [
            'formShooting' => $form->createView(),
        ]);
    }

    /**
     * Permet de modifier un lieu de tournage
     * 
     * @Route("/admin/shootings/{id}/editShooting", name="edit_shooting")
     * 
     * @param Shootings $shooting
     * @param Request $request
     * @param ObjectManager $manager
     * @param Upload $upload
     * 
     * @return Response
     */
    public function editShooting(Shootings $shooting, Request $request, ObjectManager $manager,  Upload $upload)
    {   
        $form = $this->createForm(ShootingType::class, $shooting);

        $form->handleRequest($request);

        $valid = true;

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$shooting->getId())
            {
                $shooting->setCreationDate(new \DateTime());
            }
            if(isset($request->files->get('shooting')['image']))
            {
                $fileName = $upload->upload($this->getParameter('shootings_directory'), $request->files->get('shooting')['image'], $shooting->getImage());
                
                if(!$fileName)
                {
                    $valid = false;
                    $form->get('image')->addError(new FormError("Le format d'image n'est pas accepté (jpg, jpeg, png)."));
                }
                else
                {
                    $shooting->setImage($fileName);
                }
            }       
            if($valid)
            {
                $manager->persist($shooting);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "Les modifications du lieu de tournage <strong>{$shooting->getTitle()}</strong> ont bien été enregistrée !"
                );
                
                return $this->redirectToRoute('admin_shootings_index');
            }
        }
        
        return $this->render('admin/shootings/editShooting.html.twig', [
            'formShooting' => $form->createView(),
            'shooting' => $shooting
        ]);
    }

    
    /**
     * Permet de supprimer un lieu de tournage
     *
     * @Route("/admin/shootings/{id}/deleteShooting", name="delete_shooting")
     * 
     * @param Shootings $shooting
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function deleteShooting(Shootings $shooting, ObjectManager $manager)
    {
        $manager->remove($shooting);
        $manager->flush();

        return new JsonResponse('Suppression réussi');
    }
}