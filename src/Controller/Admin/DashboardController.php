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
use Symfony\Component\HttpFoundation\RequestStack;

 #[AdminDashboard(routePath: '/admin', routeName: 'admin')]
// #[AdminDashboard(routePath: '/admin', routeName: 'admin', allowedControllers: [
//     PlatsCrudController::class,
//     CategoiresCrudController::class,
//     CommandsCrudController::class,
//     PanierCrudContorller::class,
//     UsersCrudController::class
// ])]
class DashboardController extends AbstractDashboardController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login'); // Redirect to login
        }

        $session = $this->requestStack->getSession();
        $session->invalidate();

        // Prevent caching of the admin dashboard page
        $response = $this->render('admin/dashboard.html.twig');
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');

        return $response;


      //return $this->render('admin/dashboard.html.twig');
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
