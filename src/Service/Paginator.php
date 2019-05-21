<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    /**
     * Le nom de l'entité sur laquelle on veut effectuer une pagination
     *
     * @var string
     */
    private $entityClass;

    /**
     * Le nombre d'enregistrement à récupérer
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * La page sur laquelle on se trouve actuellement
     *
     * @var integer
     */
    private $cPage = 1;
    
    /**
     * Le manager de Doctrine qui nous permet de trouver le repository dont on a besoin
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Le moteur de template Twig qui va permettre de générer le rendu de la pagination
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * Le nom de la route que l'on veut utiliser pour les boutons de la navigation
     *
     * @var string
     */
    private $route;

    /**
     * 
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
    }

    /**
     * Permet d'afficher le rendu de la navigation
     * 
     * @return void
     */
    public function display()
    {
        $this->twig->display('Templates/common/paginator.html.twig',
        [
            'page' => $this->cPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * @throws Exception
     * 
     * @return int
     */
    public function getPages(): int {
        if(empty($this->entityClass)) {

            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle vous voulez paginer. La méthode setEntityClass() de l'objet Paginator doit être utiliser.");
        }

        $total = count($this->manager
                        ->getRepository($this->entityClass)
                        ->findAll());

        return ceil($total / $this->limit);
    }

    /**
     * @throws Exception
     *
     * @return array
     */
    public function getData() 
    {
        if(empty($this->entityClass)) 
        {
            ("Vous n'avez pas spécifié l'entité sur laquelle vous voulez paginer. La méthode setEntityClass() de l'objet Paginator doit être utiliser.");
        }

        $offset = $this->cPage * $this->limit - $this->limit;

        return $this->manager
                        ->getRepository($this->entityClass)
                        ->findBy([], array('id' => 'DESC'), $this->limit, $offset);
    }

    /**
     * @param int $page
     * @return self
     */

    public function setPage(int $page):self
    {
        $this->cPage = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage():int
    {
        return $this->cPage;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit):self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit():int
    {
        return $this->limit;
    }

    /**
     * @param string $entityClass
     * @return self
     */
    public function setEntityClass(string $entityClass):self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass():string
    {
        return $this->entityClass;
    }

    /**
     * @param string $route
     * @return self
     */
    public function setRoute(string $route):self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute():string
    {
        return $route;
    }
}