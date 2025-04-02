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

#[Route('/cart/confirm', name: 'cart_confirm', methods: ['POST'])]
public function confirmOrder(
    Request $request,
    SessionInterface $session,
    EntityManagerInterface $entityManager,
    MailerService $mailerService
) {
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('cart_detail');
    }

    $cart = $session->get('cart', []);
    if (empty($cart)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('cart_detail');
    }

    // Retrieve the payment method
    $paymentMethod = $request->request->get('payment_method');
    if (!$paymentMethod) {
        $this->addFlash('danger', 'Veuillez sélectionner un mode de paiement.');
        return $this->redirectToRoute('cart_detail');
    }

    // Retrieve address option and relevant fields
    $addressOption = $request->request->get('addressOption');
    $newAddress = $request->request->get('new_address');
    $newPostalCode = $request->request->get('new_cp');

    // Create the command
    $command = new Commands();
    $command->setCommandEtat('pending');
    $command->setCommandDate(new \DateTime());
    $command->setUser($user);
    $command->setPaymentMethod($paymentMethod);

    // Set the delivery address and postal code for the command
    if ($addressOption === 'new' && !empty($newAddress) && !empty($newPostalCode)) {
        $command->setDeliveryAddresse($newAddress);
        $command->setCp($newPostalCode); // Save the new postal code for this command only
    } else {
        $command->setDeliveryAddresse($user->getAddresse());
        $command->setCp($user->getCp()); // Use the user's existing postal code
    }

    $entityManager->persist($command);

    // Calculate the total price and persist order details
    $total = 0;
    foreach ($cart as $platId => $quantity) {
        $plat = $entityManager->getRepository(Plats::class)->find($platId);
        if ($plat) {
            $detail = new Detail();
            $detail->setPlat($plat);
            $detail->setQuantite($quantity);
            $detail->setCommande($command);
            $entityManager->persist($detail);
            $total += $plat->getPlatPrix() * $quantity;
        }
    }

    $command->setTotal($total);
    $entityManager->flush(); // Save all changes

    // Prepare and send confirmation email
    $platsDetails = '';
    foreach ($cart as $platId => $quantity) {
        $plat = $entityManager->getRepository(Plats::class)->find($platId);
        if ($plat) {
            $platsDetails .= '<li>' . $plat->getPlatNom() . ' (x' . $quantity . ')</li>';
        }
    }

    $mailContent = '<p>Votre commande a été passée avec succès.</p>';
    $mailContent .= '<p><strong>Plats commandés :</strong></p>';
    $mailContent .= '<p><strong>Adresse de livraison :</strong></p>';
    $mailContent .= '<p>' . $command->getDeliveryAddresse() . '<br>Code Postal: ' . $command->getCp() . '</p>';
    $mailContent .= '<p>Email : ' . $user->getEmail() . '<br>Téléphone : ' . ($user->getTel() ?? 'Non renseigné') . '</p>';
    $mailContent .= '<ul>' . $platsDetails . '</ul>';
    $mailContent .= '<p><strong>Total : </strong>€' . number_format($total, 2, ',', ' ') . '</p>';
    $mailContent .= '<p>Vous recevrez un email une fois que l\'administrateur aura confirmé votre adresse.</p>';

    $mailerService->sendEmail(
        $user->getEmail(),
        $mailContent,
        'Confirmation de commande'
    );

    // Clear the cart and redirect
    $session->remove('cart');
    $this->addFlash('success', 'Votre commande a été passée avec succès !');
    return $this->redirectToRoute('main_plats');
}


#[Route('/cart/cancel/{id}', name: 'cart_cancel')]
public function cancelOrder(
    int $id,
    Request $request,
    EntityManagerInterface $entityManager,
    MailerService $mailerService
): Response {
    // Fetch the command by its ID
    $command = $entityManager->getRepository(Commands::class)->find($id);

    if (!$command) {
        $this->addFlash('danger', 'Commande introuvable.');
        return $this->redirectToRoute('cart_detail');
    }

    $user = $this->getUser();
    if (!$user || $command->getUser() !== $user) {
        $this->addFlash('danger', 'Vous n\'êtes pas autorisé à annuler cette commande.');
        return $this->redirectToRoute('cart_detail');
    }

   

    // Check if the user has confirmed the cancellation
    if ($request->isMethod('POST')) {
        // Debug Step: Ensure command data is correctly fetched
        if (!$command->getTotal() || !$command->getCommandDate()) {
            $this->addFlash('danger', 'Impossible de récupérer les détails de la commande.');
            return $this->redirectToRoute('cart_detail');
        }

        // Remove the command
        $entityManager->remove($command);
        $entityManager->flush();

        // Notify the admin via email
        $adminEmail = 'admin@example.com'; //admin's email address
        $emailContent = sprintf(
            '<p>L\'utilisateur <strong>%s</strong> (%s) a annulé sa commande.</p>',
            $user->getUserNom(),
            $user->getEmail(),
            'Commande annulée par l\'utilisateur'
        );

        $emailContent .= sprintf(
            '<p><strong>Détails de la commande :</strong></p>
            <ul>
                <li><strong>ID de la commande :</strong> %d</li>
                <li><strong>Date de la commande :</strong> %s</li>
                <li><strong>Total :</strong> %.2f €</li>
            </ul>',
            $command->getCommandDate()->format('d-m-Y H:i'),
            $command->getTotal(),
            'Commande ajouter par l\'utilisateur'
        );

        $mailerService->sendEmail(
            $adminEmail,
            $emailContent,
            'Commande annulée par l\'utilisateur',
            'nasira@example.com'
        );

        $this->addFlash('success', 'Votre commande a été annulée.');
        return $this->redirectToRoute('clients_index');
    }

    // Render confirmation page
    return $this->render('cart/cart.html.twig', [
        'command' => $command,
    ]);
}



}
