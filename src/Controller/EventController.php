<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\Rating;
use App\Form\EventType;
use App\Service\YouremailApiMailer;
use App\Form\EventReviewType;
use App\Repository\EventRepository;
use App\Repository\ParticipationRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('Back_Office/event-datatable.html.twig', [
            'events' => [],
        ]);
    }

    #[Route('/list-ajax', name: 'app_event_list_ajax', methods: ['GET'])]
    public function listAjax(Request $request, EventRepository $eventRepository): JsonResponse
    {
        try {
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
            [$items, $total, $filtered] = $eventRepository->findForDataTable($search, $start, $length, $orderCol, $orderDir);
            $csrf = $this->container->get('security.csrf.token_manager');
            $data = [];
            foreach ($items as $event) {
                $img = $event->getImage()
                    ? '<img src="/uploads/' . htmlspecialchars($event->getImage()) . '" alt="" class="img-thumbnail" style="max-width:80px;">'
                    : '<span class="text-muted">No image</span>';
                $token = $csrf->getToken('delete' . $event->getId())->getValue();
                $actions = '<a href="' . $this->generateUrl('app_event_edit', ['id' => $event->getId()]) . '" class="btn btn-sm btn-outline-primary me-1"><i class="material-icons-outlined" style="font-size:16px;">edit</i> Edit</a>';
                $actions .= '<form method="post" action="' . $this->generateUrl('app_event_delete', ['id' => $event->getId()]) . '" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\');"><input type="hidden" name="_token" value="' . $token . '"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="material-icons-outlined" style="font-size:16px;">delete</i> Delete</button></form>';
                $data[] = [
                    $event->getTitre(),
                    $event->getCategory() ? $event->getCategory()->getName() : '',
                    $event->getDateDebut() ? $event->getDateDebut()->format('Y-m-d H:i') : '',
                    $event->getDateFin() ? $event->getDateFin()->format('Y-m-d H:i') : '',
                    $event->getLieu(),
                    $event->getDuree(),
                    $event->getNombreMaxParticipants(),
                    $img,
                    $actions,
                ];
            }
            return new JsonResponse([
                'draw' => (int) ($query['draw'] ?? 1),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'draw' => (int) ($request->query->get('draw') ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/export/excel', name: 'app_event_export_excel', methods: ['GET'])]
    public function exportExcel(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy([], ['dateDebut' => 'ASC']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Événements');

        $headers = ['ID', 'Titre', 'Catégorie', 'Date début', 'Date fin', 'Lieu', 'Durée (min)', 'Nb max participants', 'Description'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 2;
        foreach ($events as $event) {
            $sheet->setCellValue('A' . $row, $event->getId());
            $sheet->setCellValue('B' . $row, $event->getTitre());
            $sheet->setCellValue('C' . $row, $event->getCategory()?->getName() ?? '');
            $sheet->setCellValue('D' . $row, $event->getDateDebut() ? $event->getDateDebut()->format('d/m/Y H:i') : '');
            $sheet->setCellValue('E' . $row, $event->getDateFin() ? $event->getDateFin()->format('d/m/Y H:i') : '');
            $sheet->setCellValue('F' . $row, $event->getLieu());
            $sheet->setCellValue('G' . $row, $event->getDuree());
            $sheet->setCellValue('H' . $row, $event->getNombreMaxParticipants());
            $sheet->setCellValue('I' . $row, $event->getDescription());
            $row++;
        }

        foreach (range('A', 'I') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'events_') . '.xlsx';
        $writer->save($tempFile);

        $response = new Response(file_get_contents($tempFile), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="evenements-' . date('Y-m-d') . '.xlsx"',
        ]);
        @unlink($tempFile);

        return $response;
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, YouremailApiMailer $youremailMailer): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($uploadsDirectory, $filename);
                $event->setImage($filename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            try {
                if ($youremailMailer->sendEventCreatedNotification($event)) {
                    $this->addFlash('success', 'Événement créé. Un email de notification a été envoyé à mohamednasrixxx@gmail.com.');
                } else {
                    $this->addFlash('warning', 'Événement créé, mais l\'email n\'a pas été envoyé. Renseignez YOUREMAIL_SMTP_ACCOUNT et YOUREMAIL_TEMPLATE dans .env (voir https://youremailapi.com/admin). Détails : var/log/dev.log');
                }
            } catch (\Throwable $e) {
                $this->addFlash('warning', 'Événement créé, mais l\'email a échoué : ' . $e->getMessage() . '. Consultez var/log/dev.log');
            }

            return $this->redirectToRoute('app_gestion_event', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('Back_Office/add-event.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        // Sauvegarde l'image existante si aucune nouvelle n'est ajoutée
        $existingImage = $event->getImage();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image si une nouvelle est fournie
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($uploadsDirectory, $filename);
                $event->setImage($filename);
            } else {
                $event->setImage($existingImage);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_gestion_event', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        // Reuse the Back Office add/edit layout so the edit page
        // has the same Maxton admin theme as the list & add pages.
        return $this->render('Back_Office/add-event.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_event', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/front/events', name: 'app_event_index_front', methods: ['GET'])]
    public function indexFront(EventRepository $eventRepository): Response
    {
        return $this->render('event/list_front.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    #[Route('/front/event/{id}', name: 'app_event_show_front', methods: ['GET', 'POST'])]
    public function showFront(Request $request, Event $event, EntityManagerInterface $em, RatingRepository $ratingRepository, ParticipationRepository $participationRepository): Response
    {
        $ratings = $ratingRepository->findByEvent($event);
        $user = $this->getUser();
        $userHasRated = $user && $ratingRepository->userHasRatedEvent($user, $event);
        $hasJoined = $user && $participationRepository->userHasJoinedEvent($user, $event);

        $form = null;
        if ($user && !$userHasRated) {
            $rating = new Rating();
            $rating->setUser($user);
            $rating->setEvent($event);
            $form = $this->createForm(EventReviewType::class, $rating);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($rating);
                $em->flush();
                $this->addFlash('success', 'Merci ! Votre avis a été enregistré.');
                return $this->redirectToRoute('app_event_show_front', ['id' => $event->getId()]);
            }
        }

        return $this->render('event/detailFront.html.twig', [
            'event' => $event,
            'ratings' => $ratings,
            'form' => $form ? $form->createView() : null,
            'userHasRated' => $userHasRated,
            'hasJoined' => $hasJoined,
        ]);
    }

    #[Route('/front/event/{id}/join', name: 'app_event_join', methods: ['POST'])]
    public function join(Event $event, Request $request, EntityManagerInterface $em, ParticipationRepository $participationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Connectez-vous pour rejoindre un événement.');
            return $this->redirectToRoute('app_login');
        }
        if ($participationRepository->userHasJoinedEvent($user, $event)) {
            $this->addFlash('info', 'Vous participez déjà à cet événement.');
            return $this->redirectToRoute('app_event_show_front', ['id' => $event->getId()]);
        }
        if (!$this->isCsrfTokenValid('join-event-' . $event->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('app_event_show_front', ['id' => $event->getId()]);
        }
        $participation = new Participation();
        $participation->setUser($user);
        $participation->setEvent($event);
        $em->persist($participation);
        $em->flush();
        $this->addFlash('success', 'Vous avez rejoint l\'événement avec succès.');
        return $this->redirectToRoute('app_event_show_front', ['id' => $event->getId()]);
    }
}




