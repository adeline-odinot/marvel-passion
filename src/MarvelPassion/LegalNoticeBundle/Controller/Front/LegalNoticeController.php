<?php

namespace App\MarvelPassion\LegalNoticeBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalNoticeController extends AbstractController
{
    /**
     * Permet d'afficher la page des mentions légales
     * 
     * @Route("/legalNotice", name="legal_notice")
     * 
     * @return Response
     */
    public function index()
    {
        return $this->render('LegalNoticeBundle/Ressources/views/Front/index.html.twig');
    }
}