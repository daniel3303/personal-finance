<?php

namespace App\Controller\Backend\Category;

use App\Controller\Backend\BaseController;
use App\Dto\Category\CategoryData;
use App\Entity\Category\Category;
use App\Form\Category\CategoryType;
use App\Repository\Category\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/category")
 */
class CategoryController extends BaseController {
    /**
     * @Route("/", name="backend_category_index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response {
        /** @var Category[] $categories */
        $categories = $this->paginate($categoryRepository->findAllForUserWithQuery($this->user), $request, [
            'defaultSortFieldName' => 'o.name',
            'defaultSortDirection' => 'asc'
        ]);

        return $this->render('backend/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="backend_category_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $categoryData = new CategoryData();
        $form = $this->createForm(CategoryType::class, $categoryData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_category_index');
        }

        return $this->render('backend/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_category_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function edit(Request $request, Category $category): Response {
        $categoryData = new CategoryData($category);
        $form = $this->createForm(CategoryType::class, $categoryData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_category_index', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('backend/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_category_delete", methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, Category $category): Response {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_category_index');
    }
}
