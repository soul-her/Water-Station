<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add', name: 'cart_add', methods: ['POST'])]
    public function add(Request $request, ProductRepository $productRepository, SessionInterface $session): Response
    {
        $id = $request->request->get('id');
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'quantity' => 1,
            ];
        }

        $session->set('cart', $cart);

        return $this->json([
            'message' => $product->getName() . ' added to cart!',
            'cart' => $cart
        ]);
    }

    #[Route('/view', name: 'cart_view', methods: ['GET'])]
    public function view(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        return $this->json(['cart' => $cart]);
    }

    #[Route('/remove', name: 'cart_remove', methods: ['POST'])]
    public function remove(Request $request, SessionInterface $session): Response
    {
        $id = $request->request->get('id');
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }

        return $this->json(['cart' => $cart]);
    }

    #[Route('/clear', name: 'cart_clear', methods: ['POST'])]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('cart');
        return $this->json(['message' => 'Cart cleared']);
    }
}
