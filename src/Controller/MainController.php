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
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class MainController extends AbstractController{

    private PlatsAndCategoryHelper $helper;
    private MailerService $mailerService;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(PlatsAndCategoryHelper $helper, MailerService $mailerService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->helper = $helper;
        $this->mailerService = $mailerService;
        $this->passwordHasher = $passwordHasher;
    }


    #[Route('/main', name: 'main')]
    public function index(): Response
    {
        return $this->render('main.html.twig');
    }

    //client page 
    public function clientPage(Security $security)
    {
        // Get the currently logged-in user
        $user = $security->getUser();

        // Check if a user is logged in
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }
        return $this->render('account.html.twig', [
            'user' => [
                'userNom' => $user->getUserNom(),
                'email' => $user->getEmail(),
                'addresse' => $user->getAddresse(),
                'tel' => $user->getTel(),
            ],
        ]);
        
    }

#[Route('/clients/update', name: 'update_profile', methods: ['GET', 'POST'])]
public function updateProfile(Request $request, EntityManagerInterface $entityManager, Security $security)
{
    $user = $security->getUser();


    if (!$user) {
        throw $this->createAccessDeniedException('You must be logged in to update your profile.');
    }

    // Handle profile update (same as before)
    if ($request->isMethod('POST')) {
        $user->setUserNom($request->request->get('fullname'));
        $user->setEmail($request->request->get('email'));
        $user->setAddresse($request->request->get('addresse'));
        $user->setTel($request->request->get('tel'));

        // Handles the users password change
        $password = $request->request->get('password');
        if (!empty($password)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        // this is to handle the user  photo upload
        $photo = $request->files->get('photo');
        if ($photo) {
            $photoName = uniqid() . '.' . $photo->guessExtension();
            $photo->move($this->getParameter('uploads_directory'), $photoName);
            $user->setUserPhoto($photoName);
        }

        $entityManager->persist($user);
        $entityManager->flush();
    }

    $commands = $this->getUserOrders($entityManager, $user);
    //dd($commands); // Output the commands
    die(); // Stop further execution temporarily to inspect
    
    return $this->render('clients/index.html.twig', [
        'commands' => $commands,
    ]);
 
}

private function getUserOrders(EntityManagerInterface $entityManager, $user, LoggerInterace $logger)
{

  
    $logger->info('getUserOrders function called');
    return $entityManager->getRepository(Commands::class)
        ->createQueryBuilder('c')
        ->leftJoin('c.detail', 'd')
        ->leftJoin('d.plat', 'p')
        ->addSelect('d', 'p')
        ->select('c.command_etat', 'c.command_date', 'c.total', 'p.plat_nom')
        ->where('c.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}

#[Route('/search', name: 'search')]
public function search(EntityManagerInterface $entityManager, Request $request): Response
{
    $query = $request->query->get('user_value', '');
    $categoryName = $request->query->get('category', '');

    $repository = $entityManager->getRepository(Plats::class);

    $queryBuilder = $repository->createQueryBuilder('p')
        ->join('p.categorie', 'c')
        ->addSelect('c');

    // **Filter by category name if provided**
    if (!empty($categoryName)) {
        $queryBuilder->andWhere('c.cat_nom = :category')
                     ->setParameter('category', $categoryName);
    }

    // **Apply search query if provided**
    if (!empty($query)) {
        $queryBuilder->andWhere('p.plat_nom LIKE :query') 
                     ->setParameter('query', '%' . $query . '%');
    }

    $plats = $queryBuilder->getQuery()->getResult();

    return $this->render('search.html.twig', [
        'plats' => $plats,
        'query' => $query,
        'category' => $categoryName,
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
           // dump($categoryName, $results[$categoryName]); // Debugging output
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
