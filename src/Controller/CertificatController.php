<?php

namespace App\Controller;

use App\Entity\Certificat;
use App\Form\CertificatType;
use App\Repository\CertificatRepository;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/certificat')]
final class CertificatController extends AbstractController
{
    #[Route(name: 'app_certificat_index', methods: ['GET'])]
    public function index(CertificatRepository $repository): Response
    {
        return $this->render('Back_Office/certificat-datatable.html.twig', [
            'certificats' => [],
        ]);
    }

    #[Route('/list-ajax', name: 'app_certificat_list_ajax', methods: ['GET'])]
    public function listAjax(Request $request, CertificatRepository $repository): JsonResponse
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
        $csrf = $this->container->get('security.csrf.token_manager');
        $data = [];
        foreach ($items as $certificat) {
            $token = $csrf->getToken('delete' . $certificat->getId())->getValue();
            $actions = '<a href="' . $this->generateUrl('app_certificat_pdf', ['id' => $certificat->getId()]) . '" target="_blank" class="btn btn-sm btn-outline-secondary me-1"><i class="material-icons-outlined" style="font-size:16px;">picture_as_pdf</i> PDF</a>';
            $actions .= '<a href="' . $this->generateUrl('app_certificat_edit', ['id' => $certificat->getId()]) . '" class="btn btn-sm btn-outline-primary me-1"><i class="material-icons-outlined" style="font-size:16px;">edit</i> Modifier</a>';
            $actions .= '<form method="post" action="' . $this->generateUrl('app_certificat_delete', ['id' => $certificat->getId()]) . '" style="display:inline-block;" onsubmit="return confirm(\'Supprimer ce certificat ?\');"><input type="hidden" name="_token" value="' . $token . '"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="material-icons-outlined" style="font-size:16px;">delete</i> Supprimer</button></form>';
            $data[] = [
                $certificat->getUser() ? $certificat->getUser()->getEmail() : '',
                $certificat->getEvent() ? $certificat->getEvent()->getTitre() : '',
                $certificat->getDateObtention() ? $certificat->getDateObtention()->format('d/m/Y') : '',
                '<code>' . htmlspecialchars($certificat->getCodeUnique() ?? '') . '</code>',
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

    #[Route('/new', name: 'app_certificat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $certificat = new Certificat();
        $form = $this->createForm(CertificatType::class, $certificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participation = $certificat->getParticipation();
            if ($participation) {
                $certificat->setUser($participation->getUser());
                $certificat->setEvent($participation->getEvent());
            }
            if (!$certificat->getCodeUnique() || trim((string) $certificat->getCodeUnique()) === '') {
                $certificat->setCodeUnique('CERT-' . strtoupper(substr(uniqid(), -8)));
            }
            $em->persist($certificat);
            $em->flush();

            $pdfContent = $this->generateCertificatPdf($certificat);
            $filename = 'certificat-' . preg_replace('/[^a-z0-9_-]/i', '-', $certificat->getCodeUnique()) . '.pdf';

            $response = new Response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Content-Length' => \strlen($pdfContent),
            ]);
            return $response;
        }

        return $this->render('Back_Office/add-certificat.html.twig', [
            'certificat' => $certificat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_certificat_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Certificat $certificat, EntityManagerInterface $em, ParticipationRepository $participationRepository): Response
    {
        if ($certificat->getUser() && $certificat->getEvent()) {
            $p = $participationRepository->findOneBy(['user' => $certificat->getUser(), 'event' => $certificat->getEvent()]);
            if ($p) {
                $certificat->setParticipation($p);
            }
        }
        $form = $this->createForm(CertificatType::class, $certificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participation = $certificat->getParticipation();
            if ($participation) {
                $certificat->setUser($participation->getUser());
                $certificat->setEvent($participation->getEvent());
            }
            $em->flush();
            $this->addFlash('success', 'Certificat mis à jour.');
            return $this->redirectToRoute('app_certificat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back_Office/add-certificat.html.twig', [
            'certificat' => $certificat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_certificat_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Certificat $certificat, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $certificat->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($certificat);
            $em->flush();
            $this->addFlash('success', 'Certificat supprimé.');
        }
        return $this->redirectToRoute('app_certificat_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/pdf', name: 'app_certificat_pdf', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function pdf(Certificat $certificat): Response
    {
        $pdfContent = $this->generateCertificatPdf($certificat);
        $filename = 'certificat-' . preg_replace('/[^a-z0-9_-]/i', '-', $certificat->getCodeUnique()) . '.pdf';

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Content-Length' => \strlen($pdfContent),
        ]);
    }

    private function generateCertificatPdf(Certificat $certificat): string
    {
        $user = $certificat->getUser();
        $event = $certificat->getEvent();
        $html = $this->renderView('certificat/pdf.html.twig', [
            'certificat' => $certificat,
            'nom' => $user?->getNom() ?? '',
            'prenom' => $user?->getPrenom() ?? '',
            'eventTitre' => $event?->getTitre() ?? '',
        ]);

        $dompdf = new Dompdf([
            'is_remote_enabled' => false,
            'is_html5_parser_enabled' => true,
            'default_font' => 'DejaVu Sans',
        ]);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }
}
