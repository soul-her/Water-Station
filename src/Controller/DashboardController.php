<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    // Make both "/" and "/dashboard" go to the same page
    #[Route('/', name: 'home')]
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        // Check if there are products in the DB
        $products = $em->getRepository(Product::class)->findAll();

      // Optional: seed sample products if database is empty
if (count($products) === 0) {
    $sampleProducts = [
        ['name' => 'Water Refill', 'price' => 25, 'stock' => 100, 'description' => 'For your reusable gallons'],
        ['name' => 'New Gallon', 'price' => 150, 'stock' => 50, 'description' => 'A new, pre-filled container'],
        ['name' => 'Empty Container Pickup', 'price' => 0, 'stock' => 999, 'description' => 'We’ll handle the return'],
    ];

    foreach ($sampleProducts as $p) {
        $product = new Product();
        $product->setName($p['name']);
        $product->setPrice($p['price']);
        $product->setStock($p['stock']);
        $product->setDescription($p['description']);
        $em->persist($product);
    }

    $em->flush();
}


        // Render the dashboard page
        return $this->render('dashboard/index.html.twig', [
            'products' => $products,
        ]);
    }
}
