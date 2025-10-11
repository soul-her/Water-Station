<?php
// src/Controller/DashboardController.php
namespace App\Controller;

use App\Entity\Product; // Make sure your Product entity exists
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard_index')]
    public function index(EntityManagerInterface $em): Response
    {
        // Fetch all products from the database
        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'products' => $products,
        ]);
    }
}
