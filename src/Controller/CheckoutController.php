<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout_index')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/checkout/submit', name: 'checkout_submit', methods: ['POST'])]
    public function submit(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response {
        $name = $request->request->get('name');
        $address = $request->request->get('address');
        $contact = $request->request->get('contact');

        if (!$name || !$address || !$contact) {
            $this->addFlash('error', 'Please fill in all fields.');
            return $this->redirectToRoute('checkout_index');
        }

        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('dashboard_index');
        }

        // Create main order
        $order = new Order();
        $order->setName($name)
              ->setAddress($address)
              ->setContact($contact)
              ->setStatus('Pending');

        // Add each cart item as OrderProduct
        foreach ($cart as $item) {
            $orderProduct = new OrderProduct();
            $orderProduct->setProductName($item['name']);
            $orderProduct->setQuantity($item['quantity']);
            $orderProduct->setPrice($item['price']);
            $order->addOrderProduct($orderProduct);
        }

        // Persist everything
        $entityManager->persist($order);
        $entityManager->flush();

        // Clear cart
        $session->remove('cart');

        // Redirect
        return $this->redirectToRoute('confirmation_index', [
            'id' => $order->getId(),
        ]);
    }
}
