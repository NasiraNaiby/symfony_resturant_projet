<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; // Keep this one
use App\Entity\Plats;
use App\Entity\Commands;
use App\Entity\Detail;
use App\Repository\PlatsRepository;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailerService;





final class CartController extends AbstractController{

   

// #[Route('/add/{id}', name: 'app_cart')]
// public function add(Plats $plats, SessionInterface $session, Request $request): Response
// {
//     $id = $plats->getId();
//     $cart = $session->get('cart', []);

//     // Increment quantity if exists, otherwise set it to 1
//     if (empty($cart[$id])) {
//         $cart[$id] = 1;
//     } else {
//         $cart[$id]++;
//     }

//     // Save updated cart in session
//     $session->set('cart', $cart);

//     // Get the referer URL (previous page)
//     $referer = $request->headers->get('referer');

//     // If referer is not null, redirect back to the previous page
//     if ($referer) {
//         return new RedirectResponse($referer);
//     }

//     // Fallback: If referer is empty, redirect to cart_detail by default
//     return $this->redirectToRoute('cart_detail');
// }


#[Route('/add/{id}', name: 'app_cart')]
public function add(Plats $plats, SessionInterface $session, Request $request): Response
{
    $id = $plats->getId();
    $cart = $session->get('cart', []);

    // Increment quantity if exists, otherwise set it to 1
    if (empty($cart[$id])) {
        $cart[$id] = 1;
    } else {
        $cart[$id]++;
    }

    // Save updated cart in session
    $session->set('cart', $cart);

    // Get the total cart count
    $cartCount = array_sum($cart);

    // If the request is via AJAX, return the cart count as JSON
    if ($request->isXmlHttpRequest()) {
        return new JsonResponse(['cartCount' => $cartCount]);
    }

    // For regular requests, just redirect to the cart_detail page
    return $this->redirectToRoute('cart_detail');
}






    #[Route('/cart', name: 'cart_detail')]
    public function cart( SessionInterface $session, PlatsRepository $platsRepository)
    {
        $cart = $session->get('cart', []);
        // here we have to initialize the vairiables to take the name, price , and other propertise of the plat we need 
        $data = [];
        $total = 0;
        $cartCount = 0; // This will store the total number of items
       // $session->set('cart', []);
        foreach ($cart as $id => $quantite) {
            $plats = $platsRepository->find($id);
            $data[] = [
                'plats'=>$plats,
                'quantite'=> $quantite,
            ];
            $total += $plats->getPlatPrix() * $quantite;
            $cartCount += $quantite;
        }
        return $this->render('cart.html.twig', compact('data', 'total', 'cartCount'));
    }

#[Route('/cart/remove/{id}', name: 'cart_remove')]
public function removeFromCart(SessionInterface $session, $id): Response
{
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
       if($cart[$id] > 1){
        $cart[$id]--;
    } else {
        unset($cart[$id]); // Remove item from cart
    }
    }
    $session->set('cart', $cart);

    return $this->redirectToRoute('cart_detail'); // Redirect back to cart
}

#[Route('/cart/delete/{id}', name: 'cart_delete')]
public function deleteFromCart(SessionInterface $session, Plats $plats): Response
{
    $id = $plats->getId();
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
      
        unset($cart[$id]); // delete item from cart
    }
    
    $session->set('cart', $cart);

    return $this->redirectToRoute('cart_detail'); // Redirect back to cart
}

#[Route('/cart/confirm', name: 'cart_confirm')]
public function confirmOrder(
    SessionInterface $session,
    EntityManagerInterface $entityManager,
    MailerService $mailerService // Injection of  the mailer service
): Response {
    // Checks if the user is logged in
    $user = $this->getUser();
    if (!$user) {
        $this->addFlash('warning', 'Veuillez vous connecter ou vous inscrire pour poursuivre votre commande.');
        return $this->redirectToRoute('cart_detail');
    }

    // Retrieves cart data from the session
    $cart = $session->get('cart', []);
    if (empty($cart)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('cart_detail');
    }

    // Creates a new command (order)
    $command = new Commands();
    $command->setCommandEtat('pending'); // Sets order status
    $command->setCommandDate(new \DateTime()); // Current date
    $command->setUser($user); // Link the logged-in user
    $entityManager->persist($command);

    $total = 0;

    // Loops through the cart to add detail to the command
    foreach ($cart as $platId => $quantity) {
        $plat = $entityManager->getRepository(Plats::class)->find($platId);

        if ($plat) {
            // Create a new detail
            $detail = new Detail();
            $detail->setPlat($plat);
            $detail->setQuantite($quantity);
            $detail->setCommande($command); // Link detail to the command

            // Persist the detail
            $entityManager->persist($detail);

            // Calculate the total price
            $total += $plat->getPlatPrix() * $quantity;
        }
    }

    // Set the total amount for the command
    $command->setTotal($total);

    // Save the command and details
    $entityManager->flush();

    // Send confirmation email to admin
    $mailerService->sendEmail(
        'naeibinazari@gmail.com', // the admin's email address
        '<p>Une nouvelle commande a été passée par ' . $user->getEmail() . '</p>' .
        '<p>Total: €' . $total . '</p>' .
        '<p>ID de la commande: ' . $command->getId() . '</p>',
        'Nouvelle commande passée'
    );

    // Clear the cart after confirmation
    $session->remove('cart');

    // Add flash message and redirect
    $this->addFlash('success', 'Votre commande a été passée avec succès !');
    return $this->redirectToRoute('main_plats');
}


}
