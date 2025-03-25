<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Plats;
use App\Entity\Users;
use App\Entity\Feedback;
use App\Entity\Categories;
use App\Service\PlatsAndCategoryHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\MailerService;

final class MainController extends AbstractController{

    private PlatsAndCategoryHelper $helper;
    private MailerService $mailerService;

    public function __construct(PlatsAndCategoryHelper $helper, MailerService $mailerService)
    {
        $this->helper = $helper;
        $this->mailerService = $mailerService;
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
public function accueil(EntityManagerInterface $entityManager, Request $request, MailerService $mailerService): Response
{
    // Fetch existing feedbacks (limit to 3)
    $repository = $entityManager->getRepository(Users::class);
    $feedbacksList = $repository->findAll();
    $feedbacksList = array_slice($feedbacksList, 0, 3);

    $filepath = "/uploads/";

    // Fetch categories excluding "Boissons"
    $categories = $entityManager->getRepository(Categories::class)
        ->createQueryBuilder('c')
        ->where('c.cat_nom != :category')
        ->setParameter('category', 'Boissons')
        ->select('c.cat_nom', 'c.cat_image')
        ->getQuery()
        ->getResult();

    // Fetch plats excluding "Boissons"
    $plats = $entityManager->getRepository(Plats::class)
        ->createQueryBuilder('p')
        ->leftJoin('p.categorie', 'c')
        ->where('c.cat_nom != :category')
        ->setParameter('category', 'Boissons')
        ->select('p.plat_photo')
        ->getQuery()
        ->getResult();

    // Initialize the success message
    $successMessage = null;

    // Handle form submission (POST)
    if ($request->isMethod('POST')) {
        $name = $request->request->get('name');
        $number = $request->request->get('number');
        $email = $request->request->get('email');
        $messageText = $request->request->get('message');

        // Validate form inputs
        if (!$name || !$number || !$email || !$messageText) {
            return $this->render('accueil.html.twig', [
                'plats' => $plats,
                'cats' => $categories,
                'filepath' => $filepath,
                'feedbacks' => $feedbacksList,
                'error' => 'Please fill all the fields.',
            ]);
        }

        // Save feedback to database
        $newFeedback = new Feedback();
        $newFeedback->setName($name)
                    ->setNumber($number)
                    ->setEmail($email)
                    ->setMessage($messageText);
        $entityManager->persist($newFeedback);
        $entityManager->flush();

        // Prepare email content
        $emailContent = "
            <h2>New Feedback Received</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Phone:</strong> $number</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong> $messageText</p>
        ";

        // Send email
        $mailerService->sendEmail(
            'naeibinazari@gmail.com', // Admin's email address
            $emailContent,
            'Feedback Form Submission'
        );

        // Set success message
        $successMessage = 'Merci! Votre message a été envoyé avec succès.';
    }

    // Render the page
    return $this->render('accueil.html.twig', [
        'plats' => $plats,
        'cats' => $categories,
        'filepath' => $filepath,
        'feedbacks' => $feedbacksList,
        'message' => $successMessage,
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
    public function contact(Request $request): Response
    {
       
        return $this->render('contact.html.twig');
    }

    #[Route('/contactform', name: 'main_contact_form')]
    public function contactform(Request $request): Response
    {
        $name = $request->request->get('name');
        $number = $request->request->get('number');
        $email = $request->request->get('email');
        $message = $request->request->get('message');
    
        // Properly validates that all fields are filled
        if (!$name || !$number || !$email || !$message) {
            return new Response('Please fill all the fields.', Response::HTTP_BAD_REQUEST);
        }
    
        // Prepares email content
        $emailContent = "
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Phone:</strong> $number</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong> $message</p>
        ";
    
        // Sends email using MailerService
        $this->mailerService->sendEmail(
            'naeibinazari@gmail.com', // Admin email address
            $emailContent,
            'Contact Form Data'
        );
    
        $successMessage = 'Votre message a été envoyé avec succès ! Merci de nous avoir contactés.';
        return $this->render('contact.html.twig', [
            'successMessage' => $successMessage,
            
        ]);
    }
    
    // #[Route('/feedback', name: 'main_feedback')]
    // public function feedback(EntityManagerInterface $entityManager, Request $request): Response
    // {
        
    

    //     // Render the response with both success message and categories
    //     return $this->render('accueil.html.twig', [
            
    //         'cats' => $categories,
    //     ]);
        
    // }
    
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
