<?php

namespace App\Controller;

use App\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation')]
final class ParticipationController extends AbstractController
{
    #[Route(name: 'app_participation_index', methods: ['GET'])]
    public function index(ParticipationRepository $repository): Response
    {
        return $this->render('Back_Office/participation-datatable.html.twig', [
            'participations' => [],
        ]);
    }

    #[Route('/list-ajax', name: 'app_participation_list_ajax', methods: ['GET'])]
    public function listAjax(Request $request, ParticipationRepository $repository): JsonResponse
    {
        $query = $request->query->all();
        $search = isset($query['search']) && \is_array($query['search']) && isset($query['search']['value'])
            ? trim((string) $query['search']['value'])
            : '';
        $start = (int) ($query['start'] ?? 0);
        $length = (int) ($query['length'] ?? 10);
        $length = $length <= 0 ? 100 : min($length, 100);
        $order = isset($query['order']) && \is_array($query['order']) ? ($query['order'][0] ?? null) : null;
        $orderCol = $order && isset($order['column']) ? (int) $order['column'] : 2;
        $orderDir = $order && isset($order['dir']) ? (string) $order['dir'] : 'desc';
        [$items, $total, $filtered] = $repository->findForDataTable($search, $start, $length, $orderCol, $orderDir);
        $data = [];
        foreach ($items as $p) {
            $data[] = [
                $p->getUser() ? $p->getUser()->getEmail() : '',
                $p->getEvent() ? $p->getEvent()->getTitre() : '',
                $p->getDateInscription() ? $p->getDateInscription()->format('d/m/Y H:i') : '',
            ];
        }
        return new JsonResponse([
            'draw' => (int) ($query['draw'] ?? 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
}
