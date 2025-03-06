<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Commands;
use App\Entity\Panier;
use App\Entity\Plats;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
      return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Resturant Projet Sf');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('liste des utilisateurs', 'fas fa-user', Users::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-tags', Categories::class);
        yield MenuItem::linkToCrud('Commands', 'fas fa-box', Commands::class);
        yield MenuItem::linkToCrud('Panier', 'fas fa-shopping-cart', Panier::class);
        yield MenuItem::linkToCrud('Plats', 'fas fa-utensils', Plats::class);
    }
}
