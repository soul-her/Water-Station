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

    // ✅ View Cart Page (if user visits /cart)
    #[Route('', name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    // ✅ Checkout Page (only used if accessed directly)
    #[Route('/checkout', name: 'cart_checkout', methods: ['GET'])]
    public function checkout(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    // ✅ Checkout Processing (AJAX version)
    #[Route('/checkout/process', name: 'cart_checkout_process', methods: ['POST'])]
    public function processCheckout(Request $request, SessionInterface $session): Response
    {
        $name = $request->request->get('name');
        $address = $request->request->get('address');
        $phone = $request->request->get('phone');

        // 🧾 Here, you can later save the order details to DB
        $cart = $session->get('cart', []);
        $session->remove('cart');

        return $this->json([
            'success' => true,
            'message' => "Order placed successfully for {$name}!",
            'customer' => [
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
            ],
            'cart' => $cart
        ]);
    }
}
