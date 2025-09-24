<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Example data (replace with real DB queries later)
        $orders = [
            'new' => 18,
            'scheduled' => 12,
            'history_count' => 4523,
        ];

        $containers = [
            'stock' => 320,
            'issued' => 78,
            'returned' => 46,
            'damaged' => 5,
        ];

        $reports = [
            'sales_today' => 5800,
            'sales_week' => 42000,
            'customer_activity' => 1280,
        ];

        return $this->render('dashboard/index.html.twig', [
            'orders' => $orders,
            'containers' => $containers,
            'reports' => $reports,
        ]);
    }
}
