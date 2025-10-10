<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/place', name: 'order_place')]
    public function place(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('danger', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        // COD-only policy (no online payment)
        $session->remove('cart');
        $this->addFlash('success', 'Order placed successfully! (Cash on Delivery only)');

        return $this->redirectToRoute('home');
    }

    #[Route('/cancel', name: 'order_cancel')]
    public function cancel(SessionInterface $session): Response
    {
        $session->remove('cart');
        $this->addFlash('info', 'Order has been cancelled.');

        return $this->redirectToRoute('home');
    }
}
