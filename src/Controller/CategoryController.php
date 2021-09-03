<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category.index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="category.create")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $category-> setCreatedAt(new \DateTime());
        $category-> setUpdatedAt(new \DateTime());

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'The category has been created!'
            );

            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/create.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category.show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="category.update")
     */
    public function update(Request $request, Category $category): Response
    {
        $category-> setUpdatedAt(new \DateTime());
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'The category has been updated!'
            );
            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/update.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category.delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category): Response
    {

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        $this->addFlash(
            'success',
            'The category has been deleted!'
        );

        return $this->redirectToRoute('category.index');
    }

}
