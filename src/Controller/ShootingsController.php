<?php

namespace App\Controller;

use App\Repository\ShootingsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShootingsController extends AbstractController
{
    /**
     * Permet d'afficher la page des tournages
     * 
     * @Route("/shootings", name="shootings")
     * 
     * @param ShootingsRepository $repo
     */
    public function index(ShootingsRepository $repo)
    {
        $shootings = $repo->findAll();

        return $this->render('shootings/index.html.twig', [
            'shootings' => $shootings,
        ]);
    }

    /**
     * Permet de récupérer les données d'un tournage
     * 
     * @Route("/shootings/{id}", name="show_shootings")
     * 
     * @param ShootingsRepository $repo
     * @var $id
     */
    public function show(ShootingsRepository $repo, $id)
    {
        $shootings = $repo->find($id);

        $json = [];
        $json['id'] = $shootings->getId();
        $json['title'] = $shootings->getTitle();
        $json['description'] = $shootings->getDescription();
        $json['image'] = $shootings->getImage();
        $json['address'] = $shootings->getAddress();

        if ($shootings->getMovie() == NULL)
        {
            $json['type'] = 'Série';
        }
        else
        {
            $json['type'] = 'Film';
        }

        return new JsonResponse($json);
    }
}