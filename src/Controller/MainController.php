<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Plats;
use App\Entity\Categories;
use App\Service\PlatsAndCategoryHelper;

final class MainController extends AbstractController{

    private PlatsAndCategoryHelper $helper;

    public function __construct(PlatsAndCategoryHelper $helper)
    {
        $this->helper = $helper;
    }


    #[Route('/main', name: 'main')]
    public function index(): Response
    {
        return $this->render('main.html.twig');
    }

    #[Route('/', name: 'main_accueil')]
    public function accueil(EntityManagerInterface $entityManager): Response
    {
        $plats = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->leftJoin('p.categorie', 'c') // Join with the Categories entity
        ->where('c.cat_nom != :category')  // Filter out "Boissons"
        ->setParameter('category', 'Boissons')
        ->select('p.plat_photo')
        ->getQuery()
        ->getResult();

    return $this->render('accueil.html.twig', [
        'plats' => $plats,
    ]);

        
    }

    #[Route('/catégorie', name: 'main_catégorie')]
    public function catégorie(): Response
    {
        // List of category names
        $categoryNames = ['tacos', 'Burgers', 'pizza', 'Petit-déjeuner', 'SeaFoods', 'Salads', 'Boissons'];

        // Initialize an empty array to store results
        $results = [];

        // Fetch data for each category dynamically
        foreach ($categoryNames as $categoryName) {
            $results[$categoryName] = $this->helper->getCategoryByPlats($categoryName);
            dump($categoryName, $results[$categoryName]); // Debugging output
        }
        
        // Pass the results to the template
        return $this->render('catégorie.html.twig', [
            'results' => $results
        ]);
    }
    
    

    #[Route('/contact', name: 'main_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/plats', name: 'main_plats')]
    public function plats(EntityManagerInterface $entityManager): Response
    {
        // Create a map of categories to their data
        $categories = [
            'Tacos' => $this->helper->getCategoryByPlats('tacos'),
            'Fastfood' => $this->helper->getPlatsByCategory(['Burgers', 'pizza'], true),
            'Seafoods' => $this->helper->getCategoryByPlats('SeaFoods'),
            'Petit-déjeuner' => $this->helper->getCategoryByPlats('Petit-déjeuner'),
            'Salads' => $this->helper->getCategoryByPlats('Salads'),
            'Boissons' => $this->helper->getCategoryByPlats('Boissons'),
        ];
    
        // Pass the consolidated categories array to Twig
        return $this->render('plats.html.twig', [
            'categories' => $categories,
        ]);
    }
    

    #[Route('/clients', name: 'clients_index')]
    #[IsGranted('ROLE_USER')] // Allow only users with ROLE_USER
    public function clients(): Response
    {   
        return $this->render('clients/index.html.twig');
    }
  
}
