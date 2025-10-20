<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(Request $request): Response
    {
        // Capture active tab (default to 'orders')
        $activeTab = $request->query->get('tab', 'orders');

        return $this->render('admin/admin.html.twig', [
            'active_tab' => $activeTab,
        ]);
    }
}
