<?php

namespace App\MarvelPassion\UserBundle\Controller\Front;

use App\MarvelPassion\UserBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Permet d'afficher les informations d'un utilisateur
     * 
     * @Route("/user/{pseudo}", name="show_user")
     * 
     * @param Users $user
     * 
     * @return Response
     */
    public function index(Users $user)
    {
        return $this->render('UserBundle/Ressources/views/Front/user/index.html.twig', [
            'user' => $user
        ]);
    }
}