<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin")
*/

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="accueil_admin", methods={"GET"})
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/indexAdmin.html.twig');
    }
}