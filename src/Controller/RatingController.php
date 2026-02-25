<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rating')]
final class RatingController extends AbstractController
{
    #[Route(name: 'app_rating_index', methods: ['GET'])]
    public function index(RatingRepository $repository): Response
    {
        return $this->render('Back_Office/rating-datatable.html.twig', [
            'ratings' => [],
        ]);
    }

    #[Route('/list-ajax', name: 'app_rating_list_ajax', methods: ['GET'])]
    public function listAjax(Request $request, RatingRepository $repository): JsonResponse
    {
        $query = $request->query->all();
        $search = isset($query['search']) && \is_array($query['search']) && isset($query['search']['value'])
            ? trim((string) $query['search']['value'])
            : '';
        $start = (int) ($query['start'] ?? 0);
        $length = (int) ($query['length'] ?? 10);
        $length = $length <= 0 ? 100 : min($length, 100);
        $order = isset($query['order']) && \is_array($query['order']) ? ($query['order'][0] ?? null) : null;
        $orderCol = $order && isset($order['column']) ? (int) $order['column'] : 4;
        $orderDir = $order && isset($order['dir']) ? (string) $order['dir'] : 'desc';
        [$items, $total, $filtered] = $repository->findForDataTable($search, $start, $length, $orderCol, $orderDir);
        $csrf = $this->container->get('security.csrf.token_manager');
        $data = [];
        foreach ($items as $rating) {
            $comment = $rating->getCommentaire() ?? '-';
            $comment = mb_strlen($comment) > 50 ? mb_substr($comment, 0, 50) . '...' : $comment;
            $token = $csrf->getToken('delete' . $rating->getId())->getValue();
            $actions = '<a href="' . $this->generateUrl('app_rating_edit', ['id' => $rating->getId()]) . '" class="btn btn-sm btn-outline-primary me-1"><i class="material-icons-outlined" style="font-size:16px;">edit</i> Modifier</a>';
            $actions .= '<form method="post" action="' . $this->generateUrl('app_rating_delete', ['id' => $rating->getId()]) . '" style="display:inline-block;" onsubmit="return confirm(\'Supprimer cet avis ?\');"><input type="hidden" name="_token" value="' . $token . '"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="material-icons-outlined" style="font-size:16px;">delete</i> Supprimer</button></form>';
            $data[] = [
                $rating->getUser() ? $rating->getUser()->getEmail() : '',
                $rating->getEvent() ? $rating->getEvent()->getTitre() : '',
                $rating->getNote() . '/5',
                $comment,
                $rating->getDateCreation() ? $rating->getDateCreation()->format('d/m/Y') : '',
                $actions,
            ];
        }
        return new JsonResponse([
            'draw' => (int) ($query['draw'] ?? 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    #[Route('/new', name: 'app_rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rating);
            $em->flush();
            $this->addFlash('success', 'Avis enregistré.');
            return $this->redirectToRoute('app_rating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back_Office/add-rating.html.twig', [
            'rating' => $rating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rating_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Rating $rating, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Avis mis à jour.');
            return $this->redirectToRoute('app_rating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back_Office/add-rating.html.twig', [
            'rating' => $rating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rating_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Rating $rating, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($rating);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé.');
        }
        return $this->redirectToRoute('app_rating_index', [], Response::HTTP_SEE_OTHER);
    }
}
