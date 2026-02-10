<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\TestRepository;
use App\Repository\TypedetestRepository;
use App\Repository\TypeRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back')] // Préfixe pour toutes les routes de ce contrôleur
final class BackOfficeController extends AbstractController
{
    #[Route('/', name: 'app_back_office', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    // Integration event backOffice
    #[Route('/gestion_event', name: 'app_gestion_event', methods: ['GET'])]
    public function gestionEvent(EventRepository $eventRepository, CategoryRepository $categoryRepository): Response
    {
        $events = $eventRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('Back_Office/event-datatable.html.twig', [
            'events' => $events,
            'categories' => $categories,
        ]);
    }

}
