<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * Permet d'afficher l'administration
     * 
     * @Route("/admin", name="admin")
     * 
     * @Security("is_granted('ROLE_ADMIN')", message="Vous devez être connecté en tant qu'administrateur pour accéder à cette page.")
     * 
     * @return Response
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
