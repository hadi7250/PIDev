<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('Back_Office/category-datatable.html.twig', [
            'categories' => [],
        ]);
    }

    #[Route('/list-ajax', name: 'app_category_list_ajax', methods: ['GET'])]
    public function listAjax(Request $request, CategoryRepository $categoryRepository): JsonResponse
    {
        $query = $request->query->all();
        $search = isset($query['search']) && \is_array($query['search']) && isset($query['search']['value'])
            ? trim((string) $query['search']['value'])
            : '';
        $start = (int) ($query['start'] ?? 0);
        $length = (int) ($query['length'] ?? 10);
        $length = $length <= 0 ? 100 : min($length, 100);
        $order = isset($query['order']) && \is_array($query['order']) ? ($query['order'][0] ?? null) : null;
        $orderCol = $order && isset($order['column']) ? (int) $order['column'] : 0;
        $orderDir = $order && isset($order['dir']) ? (string) $order['dir'] : 'asc';
        [$items, $total, $filtered] = $categoryRepository->findForDataTable($search, $start, $length, $orderCol, $orderDir);
        $csrf = $this->container->get('security.csrf.token_manager');
        $data = [];
        foreach ($items as $category) {
            $token = $csrf->getToken('delete' . $category->getId())->getValue();
            $actions = '<a href="' . $this->generateUrl('app_category_edit', ['id' => $category->getId()]) . '" class="btn btn-sm btn-outline-primary me-1"><i class="material-icons-outlined" style="font-size:16px;">edit</i> Edit</a>';
            $actions .= '<form method="post" action="' . $this->generateUrl('app_category_delete', ['id' => $category->getId()]) . '" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\');"><input type="hidden" name="_token" value="' . $token . '"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="material-icons-outlined" style="font-size:16px;">delete</i> Delete</button></form>';
            $data[] = [$category->getName(), $category->getEvents()->count(), $actions];
        }
        return new JsonResponse([
            'draw' => (int) ($query['draw'] ?? 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back_Office/add-category.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back_Office/add-category.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
