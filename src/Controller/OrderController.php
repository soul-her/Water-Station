<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        // ✅ Render order list as a fragment for the admin dashboard
        return $this->render('order/index_fragment.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

   #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $order = new Order();
    $form = $this->createForm(OrderType::class, $order);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $order->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('success', 'Order created successfully!');
        return $this->redirectToRoute('app_admin_dashboard', ['tab' => 'orders']);
    }

    return $this->render('order/new.html.twig', [
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Order updated successfully!');
            return $this->redirectToRoute('app_admin_dashboard', ['tab' => 'orders']);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/cancel', name: 'app_order_cancel', methods: ['POST'])]
    public function cancel(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('cancel'.$order->getId(), $request->request->get('_token'))) {
            $order->setStatus('Cancelled');
            $entityManager->flush();

            $this->addFlash('success', 'Order cancelled successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_admin_dashboard', ['tab' => 'orders']);
    }

    #[Route('/{id}/delete', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
            $this->addFlash('success', 'Order deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_admin_dashboard', ['tab' => 'orders']);
    }
}
