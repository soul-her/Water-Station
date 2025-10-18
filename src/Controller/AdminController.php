<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class AdminController extends AbstractController
{
    /**
     * This route will serve the main Admin Dashboard page.
     * It fetches data for Orders, Products, Stock, and Categories 
     * and passes it all to the single-page Twig template.
     */
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    // IMPORTANT: You would add a security annotation here to protect the route:
    // #[IsGranted('ROLE_ADMIN')]
    public function dashboard(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em // Use EntityManager for general tasks like stock
    ): Response
    {
        // 1. Fetch data for the Orders section
        // We typically fetch the most recent N orders for a dashboard view
        $orders = $orderRepository->findBy([], ['id' => 'DESC'], 10);

        // 2. Fetch data for the Products section
        $products = $productRepository->findAll();

        // 3. Fetch data for the Categories section
        $categories = $categoryRepository->findAll();

        // 4. Fetch data for the Stock section
        // Note: Stock management might be part of the Product entity,
        // so we'll pass the full product list, but you might have a dedicated Stock entity.
        $stockReport = $productRepository->findLowStockProducts(5); // Mock function

        // Pass all collected data to the combined dashboard template
        return $this->render('admin/admin.html.twig', [
            // Data for the 'Orders' tab
            'orders' => $orders,

            // Data for the 'Products' tab
            'products' => $products,

            // Data for the 'Stock Inventory' tab
            'stockReport' => $stockReport,

            // Data for the 'Categories' tab
            'categories' => $categories,
            
            // NOTE: If you need to render forms (like OrderType or ProductType) here, 
            // you would also create and pass the form views: 
            // 'productForm' => $this->createForm(ProductType::class)->createView(),
        ]);
    }
}
