<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController{
    #[Route('/main', name: 'main')]
    public function index(): Response
    {
        return $this->render('main.html.twig');
    }

    #[Route('/', name: 'main_accueil')]
    public function accueil(): Response
    {
        return $this->render('accueil.html.twig');
    }

    #[Route('/catégorie', name: 'main_catégorie')]
    public function catégorie(): Response
    {
        return $this->render('catégorie.html.twig');
    }

    #[Route('/contact', name: 'main_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/plats', name: 'main_plats')]
    public function plats(): Response
    {
        return $this->render('plats.html.twig');
    }

    #[Route('/clients', name: 'clients_index')]
    public function clients(): Response
    {
        return $this->render('clients/index.html.twig');
    }
  
}
