<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product.index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="product.create")
     */
    public function create(Request $request): Response
    {
        $product = new Product();
        $product-> setCreatedAt(new \DateTime());
        $product-> setUpdatedAt(new \DateTime());

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                'success',
                'The product has been created!'
            );
            return $this->redirectToRoute('product.index');
        }

        return $this->render('product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product.show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="product.update")
     */
    public function update(Request $request, Product $product): Response
    {
        $product-> setUpdatedAt(new \DateTime());
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
           $this->addFlash(
                'success',
                'The product has been updated!'
            );
            return $this->redirectToRoute('product.index');
        }

        return $this->render('product/update.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product.delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        $this->addFlash(
            'success',
            'The product has been deleted!'
        );
        return $this->redirectToRoute('product.index');
    }

}
