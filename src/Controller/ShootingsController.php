<?php

namespace App\Controller;

use App\Repository\ShootingsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShootingsController extends AbstractController
{
    /**
     * @Route("/shootings", name="shootings")
     */
    public function index(ShootingsRepository $repo)
    {
        $shootings = $repo->findAll();

        return $this->render('shootings/index.html.twig', [
            'shootings' => $shootings,
        ]);
    }

    /**
     * @Route("/shootings/{id}", name="show_shootings")
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
