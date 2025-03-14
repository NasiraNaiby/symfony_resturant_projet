<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Plats;
use App\Entity\Categories;
use App\Service\PlatsAndCategoryHelper;
use Doctrine\Persistence\ManagerRegistry;

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

    #[Route('/search', name: 'search')]
public function search(EntityManagerInterface $entityManager, Request $request): Response
{
    // Get the search query from the request
    $query = $request->query->get('user_value', '');

    // Get the repository for the Plats entity
    $repository = $entityManager->getRepository(Plats::class);

    // Perform a search: For example, find plats by name or description
    if (!empty($query)) {
        $plats = $repository->createQueryBuilder('p')
            ->where('p.plat_nom LIKE :query OR p.plat_description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    } else {
        // If no query is provided, retrieve all plats
        $plats = $repository->findAll();
    }

    // Render the results in the Twig template
    return $this->render('search.html.twig', [
        'plats' => $plats,
        'query' => $query, // Pass the search term for user feedback
    ]);
}


#[Route('/', name: 'main_accueil')]
public function accueil(EntityManagerInterface $entityManager): Response
{
    // Fetch categories excluding "Boissons"
    $cats = $entityManager->getRepository(Categories::class)
        ->createQueryBuilder('c') // Use the correct entity: Categories
        ->where('c.cat_nom != :category') // Filter out "Boissons"
        ->setParameter('category', 'Boissons')
        ->select('c.cat_nom', 'c.cat_image') // Select fields from Categories
        ->getQuery()
        ->getResult();

    // Fetch plats (join with Categories to filter by category)
    $plats = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->leftJoin('p.categorie', 'c') // Ensure the join is with the Categories entity
        ->where('c.cat_nom != :category') // Exclude plats from "Boissons" category
        ->setParameter('category', 'Boissons')
        ->select('p.plat_photo') // Select the specific fields from Plats
        ->getQuery()
        ->getResult();

    return $this->render('accueil.html.twig', [
        'plats' => $plats,
        'cats' => $cats,
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
