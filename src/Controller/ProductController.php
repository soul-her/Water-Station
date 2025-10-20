<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem; // Import Filesystem for file deletion

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        // Renders the fragment template for embedding in the Admin Dashboard
        return $this->render('product/index_fragment.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    // ---------------------------------------------------------------------

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/products',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed.');
                    // Optionally log the error or rethrow
                }

                $product->setImage($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product added successfully! 🎉');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    // ---------------------------------------------------------------------

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    // ---------------------------------------------------------------------

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // NOTE: If you pass the existing image string to ProductType, Symfony will try to save it. 
        // Best practice is to set the 'required' option for the image field to false in ProductType.
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the filename of the *old* image before checking for a new upload
            $oldImageFilename = $product->getImage();
            
            // Handle new image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Remove the old file if it exists and a new one is uploaded
                if ($oldImageFilename) {
                    $filesystem = new Filesystem();
                    $fileToDelete = $this->getParameter('kernel.project_dir') . '/public/uploads/products/' . $oldImageFilename;
                    if ($filesystem->exists($fileToDelete)) {
                        $filesystem->remove($fileToDelete);
                    }
                }
                
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/products',
                        $newFilename
                    );
                    $product->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed during update.');
                }
            }
            
            // If no new image is uploaded, the existing image filename (if any) remains on the entity.
            
            $entityManager->flush();
            $this->addFlash('success', 'Product updated successfully! 💾');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    // ---------------------------------------------------------------------

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        // FIX: Use $request->request->get() to access POST data from a standard form submission.
        // request->getPayload()->getString() is for JSON/API payloads (Symfony 6.2+).
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            
            // 1. Delete the associated image file if it exists
            $imageFilename = $product->getImage();
            if ($imageFilename) {
                $fileToDelete = $this->getParameter('kernel.project_dir') . '/public/uploads/products/' . $imageFilename;

                if ($filesystem->exists($fileToDelete)) {
                    $filesystem->remove($fileToDelete);
                }
            }

            // 2. Delete the entity from the database
            $entityManager->remove($product);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product deleted successfully! 🗑️');
        } else {
            $this->addFlash('error', 'Invalid CSRF token. Product not deleted.');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}