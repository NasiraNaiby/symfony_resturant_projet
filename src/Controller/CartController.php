<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; // Keep this one
use App\Entity\Plats;
use App\Repository\PlatsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


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
public function confirmOrder(SessionInterface $session): Response
{
    // Check if the user is logged in
    if (!$this->getUser()) {

        $this->addFlash('warning', 'Please login or register to proceed with your order.');
        // Redirect guest users to the registration page
        return $this->redirectToRoute('cart_detail');
    }

    // If logged in, redirect to checkout
    return $this->redirectToRoute('cart_checkout');
}



// #[Route('/cart/count', name: 'cart_count')]
// public function getCartCount(SessionInterface $session): JsonResponse
// {
//      $cart = $session->get('cart', []);
//      $cartCount = array_sum($cart); // Calculate the total number of items in the cart
//      return new JsonResponse(['cartCount' => $cartCount]); // Return the updated cart count
//    // return $this->redirectToRoute('main_plats');
// }

}
