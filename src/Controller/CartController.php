<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function add(Request $request, SessionInterface $session): Response
    {
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $quantity = (int) $request->request->get('quantity', 1);

        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        $session->set('cart', $cart);
        $this->addFlash('success', 'Item added to cart.');

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST', 'GET'])]
    public function remove(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
            $this->addFlash('success', 'Item removed.');
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/increase/{id}', name: 'cart_increase', methods: ['POST'])]
    public function increase(SessionInterface $session, int $id): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }

        $session->set('cart', $cart);
        $this->addFlash('success', 'Increased quantity of ' . $cart[$id]['name']);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease', methods: ['POST'])]
    public function decrease(SessionInterface $session, int $id): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']--;

            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
                $this->addFlash('success', 'Item removed from cart.');
            } else {
                $this->addFlash('success', 'Decreased quantity of ' . $cart[$id]['name']);
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }
}
