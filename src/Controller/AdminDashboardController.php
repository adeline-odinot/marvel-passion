<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher l'administration
     * 
     * @Route("/admin", name="admin")
     * 
     * @return Response
     */
    public function index(ObjectManager $manager, Stats $stats)
    {
        $getStats = $stats->getStats();

        return $this->render('admin/dashboard/index.html.twig',
        [
            'stats' => $getStats
            
        ]);
    }
}
