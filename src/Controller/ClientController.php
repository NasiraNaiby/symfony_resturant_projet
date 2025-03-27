<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

final class ClientController extends AbstractController{
    #[Route('/clients', name: 'clients_index')]
    #[IsGranted('ROLE_USER')] // Allow only users with ROLE_USER
    public function clients(EntityManagerInterface $entityManager): Response
    {  
        $repository = $entityManager->getRepository(Users::class); 
        // $user = $repository->findAll();
        $user = $this->getUser();
        //$commands = $this->getUserOrders($entityManager, $user);
        dump($user);
        return $this->render('clients/index.html.twig', ['user'=>$user]);
        
    }



 
}
