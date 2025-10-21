<?php

namespace App\Controller;

use App\Entity\Container;
use App\Form\Container1Type;
use App\Repository\ContainerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/container')]
final class ContainerController extends AbstractController
{
    #[Route(name: 'app_container_index', methods: ['GET'])]
    public function index(ContainerRepository $containerRepository): Response
    {
        return $this->render('container/index.html.twig', [
            'containers' => $containerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_container_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $container = new Container();
        $form = $this->createForm(Container1Type::class, $container);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($container);
            $entityManager->flush();

            return $this->redirectToRoute('app_container_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('container/new.html.twig', [
            'container' => $container,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_container_show', methods: ['GET'])]
    public function show(Container $container): Response
    {
        return $this->render('container/show.html.twig', [
            'container' => $container,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_container_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Container $container, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Container1Type::class, $container);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_container_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('container/edit.html.twig', [
            'container' => $container,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_container_delete', methods: ['POST'])]
    public function delete(Request $request, Container $container, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$container->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($container);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_container_index', [], Response::HTTP_SEE_OTHER);
    }
}
