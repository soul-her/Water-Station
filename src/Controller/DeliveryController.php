<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeliveryController extends AbstractController
{
    #[Route('/delivery/order', name: 'app_delivery_order')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $order = new Order();
        $order->addOrderProduct(new OrderProduct());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enforce strict COD policy
            if ($order->getPaymentMethod() !== 'COD') {
                $this->addFlash('error', 'Only Cash on Delivery is allowed.');
                return $this->redirectToRoute('app_delivery_order');
            }

            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Order placed successfully under COD policy!');
            return $this->redirectToRoute('order_index');
        }

        return $this->render('delivery/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
