<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Plats;
use App\Entity\Users;
use App\Entity\Categories;
use App\Service\PlatsAndCategoryHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    // #[Route('/checkout', name: 'cart_checkout')]
    // public function checkout(): Response
    // {
    //     return $this->render('command.html.twig');
    // }

    #[Route('/search', name: 'search')]
    public function search(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Retrieve the search query and category from the request
        $query = $request->query->get('user_value', '');
        $categoryName = $request->query->get('category', '');
    
        // Get the repository for the Plats entity
        $repository = $entityManager->getRepository(Plats::class);
    
        // Build the query
        $queryBuilder = $repository->createQueryBuilder('p')
            ->join('p.categorie', 'c') // Join the Categories entity
            ->addSelect('c');         // Include category data
  
    
        // Filter by category name if provided
        if (!empty($categoryName)) {
            $queryBuilder->andWhere('c.cat_nom = :category')
                         ->setParameter('category', $categoryName);
        }
    
        // Execute the query and get results
        $plats = $queryBuilder->getQuery()->getResult();
    
        // Render the results in the Twig template
        return $this->render('search.html.twig', [
            'plats' => $plats,           // Pass plats to the template
            'query' => $query,           // Pass the search term for feedback
            'category' => $categoryName, // Pass the selected category for feedback and highlighting
        ]);
    }
    


#[Route('/', name: 'main_accueil')]
public function accueil(EntityManagerInterface $entityManager): Response
{

    $repository = $entityManager->getRepository(Users::class);
    $feedbacks = $repository->findAll();
    $feedbacks = array_slice($feedbacks, 0, 3);


    $filepath = "/uploads/";
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
        'filepath'=>$filepath,
        'feedbacks'=>$feedbacks,
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
    
  
}
