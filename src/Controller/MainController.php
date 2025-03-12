<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Plats;
use App\Entity\Categories;
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
    public function catégorie(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categories::class) // ✅ Fetch categories
            ->createQueryBuilder('c')
            ->leftJoin('c.plats', 'p') // ✅ Join plats table
            ->addSelect('p') // ✅ Fetch plats data
            ->getQuery()
            ->getResult();
    
        return $this->render('catégorie.html.twig', [
            'categories' => $categories,  // ✅ Now $categories is correctly passed
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
        $plats = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')  // Join the 'categorie' property from Plats
        ->where('c.cat_nom = :category')  // Use 'cat_nom' as defined in your entity => only accepts single value here ('c.cat_nom = :category')
        ->setParameter('category', 'tacos')
        ->getQuery()
        ->getResult();


        $fastfoods = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')  // Join with Categories
        ->where('c.cat_nom IN (:categories)')  // Use IN clause for multiple values('c.cat_nom IN (:categories)') 
        ->setParameter('categories', ['Burgers', 'pizza'])  // Pass an array of values
        ->getQuery()
        ->getResult();

        $seafoods = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')  // Join with Categories
        ->where('c.cat_nom = :category') 
        ->setParameter('category', 'Sea foods')  // Pass an array of values
        ->getQuery()
        ->getResult();

        $pds = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')  // Join with Categories
        ->where('c.cat_nom = :category')  
        ->setParameter('category', 'Petit-déjeuner')  // Pass an array of values
        ->getQuery()
        ->getResult();

        $salads = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')
        ->where('c.cat_nom = :category')
        ->setParameter('category', 'Salads')
        ->getQuery()
        ->getResult();

        $boissons = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->join('p.categorie', 'c')
        ->where('c.cat_nom = :category')
        ->setParameter('category', 'Boissons')
        ->getQuery()
        ->getResult();

    
    
    // Render the template and pass the plats data to it
    return $this->render('plats.html.twig', [
        'plats' => $plats,  // Pass plats to Twig
        'fastfoods' => $fastfoods,
        'seafoods' =>$seafoods,
        'pds'=>$pds,
        'salads'=>$salads,
        'boissons'=>$boissons,
    ]);
    
    
    }

    #[Route('/clients', name: 'clients_index')]
    #[IsGranted('ROLE_USER')] // Allow only users with ROLE_USER
    public function clients(): Response
    {   
        return $this->render('clients/index.html.twig');
    }
  
}
