<?php

declare(strict_types=1);

namespace App\Twig;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ChatbotDataExtension extends AbstractExtension
{
    public function __construct(
        private EventRepository $eventRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('chatbot_data', [$this, 'getChatbotData']),
        ];
    }

    /**
     * @return array{events: array<int, array{titre: string, dateDebut: string, dateFin: string, lieu: string, description: string, duree: int, nombreMaxParticipants: int, category: string}>, categories: array<int, array{name: string, eventCount: int}>}
     */
    public function getChatbotData(): array
    {
        $events = $this->eventRepository->findBy([], ['dateDebut' => 'ASC']);
        $categories = $this->categoryRepository->findAll();

        $eventsContext = [];
        foreach ($events as $event) {
            $eventsContext[] = [
                'titre' => $event->getTitre() ?? '',
                'dateDebut' => $event->getDateDebut()?->format('Y-m-d H:i') ?? '',
                'dateFin' => $event->getDateFin()?->format('Y-m-d H:i') ?? '',
                'lieu' => $event->getLieu() ?? '',
                'description' => $event->getDescription() ?? '',
                'duree' => $event->getDuree() ?? 0,
                'nombreMaxParticipants' => $event->getNombreMaxParticipants() ?? 0,
                'category' => $event->getCategory()?->getName() ?? '',
            ];
        }

        $categoriesContext = [];
        foreach ($categories as $category) {
            $categoriesContext[] = [
                'name' => $category->getName() ?? '',
                'eventCount' => $category->getEvents()->count(),
            ];
        }

        return ['events' => $eventsContext, 'categories' => $categoriesContext];
    }
}
