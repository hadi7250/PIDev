<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ChatService
{
    private const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openaiApiKey,
    ) {
    }

    /**
     * @param array<int, array{titre: string, dateDebut: string, dateFin: string, lieu: string, description: string, duree: int, nombreMaxParticipants: int, category: string}> $eventsContext
     * @param array<int, array{name: string, eventCount: int}> $categoriesContext
     */
    public function ask(string $userMessage, array $eventsContext, array $categoriesContext): string
    {
        $key = trim((string) $this->openaiApiKey);
        if ($key === '' || str_starts_with($key, 'your_openai_api_key')) {
            throw new \InvalidArgumentException('OPENAI_API_KEY is not set or is still the placeholder. Set it in .env or .env.local, then run: php bin/console cache:clear');
        }

        $systemPrompt = $this->buildSystemPrompt($eventsContext, $categoriesContext);

        $response = $this->httpClient->request('POST', self::OPENAI_API_URL, [
            'headers' => [
                'Authorization' => 'Bearer ' . $key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 1024,
                'temperature' => 0.3,
            ],
        ]);

        $data = $response->toArray();
        $content = $data['choices'][0]['message']['content'] ?? '';

        return trim($content);
    }

    /**
     * @param array<int, array{titre: string, dateDebut: string, dateFin: string, lieu: string, description: string, duree: int, nombreMaxParticipants: int, category: string}> $eventsContext
     * @param array<int, array{name: string, eventCount: int}> $categoriesContext
     */
    private function buildSystemPrompt(array $eventsContext, array $categoriesContext): string
    {
        $eventsJson = json_encode($eventsContext, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $categoriesJson = json_encode($categoriesContext, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
You are a chatbot for an event platform. You answer ONLY the following types of questions, using ONLY the data provided below.

ALLOWED QUESTION TYPES:

1) EVENTS
- What events are available? / Quels événements sont disponibles?
- Give me the list of events. / Donne-moi la liste des événements.
- When does [event name] take place? / Quand a lieu [nom de l'événement] ?
- Where does [event name] take place? / Où se déroule [nom] ?
- Describe the event [name]. / Décris-moi l'événement [nom].
- How many places for [event name]? / Combien de places pour [nom] ?

2) CATEGORIES
- What are the categories? / Quelles sont les catégories ?
- List the event categories. / Liste les catégories d'événements.
- What events are in category [name]? / Quels événements sont dans la catégorie [nom] ?
- Filter by category [name]. / Filtre par catégorie [nom].

3) FILTERING
- Events in category [X]. / Les événements de la catégorie [X].
- Events at [location]. / Événements à [lieu].
- Events on [date]. / Événements en [date].

RULES:
- Answer in the same language as the user (French or English).
- Use ONLY the events and categories in the data below. Do not invent any event, date, location, or category.
- If an event or category is not in the list, say clearly: "Cet événement n'existe pas dans notre base." / "This event does not exist in our database." (or for categories: say it does not exist).
- If the user asks something that is NOT one of the question types above (e.g. general chat, other topics), answer briefly: "Je ne peux répondre qu'aux questions sur les événements et les catégories. Posez-moi par exemple la liste des événements ou des catégories." (or in English: "I can only answer questions about events and categories. Try asking for the list of events or categories.")

DATA - EVENTS (use only these):
{$eventsJson}

DATA - CATEGORIES (with number of events):
{$categoriesJson}

Answer concisely and only from this data.
PROMPT;
    }

    /**
     * @param Event[] $events
     * @return array<int, array{titre: string, dateDebut: string, dateFin: string, lieu: string, description: string, duree: int, nombreMaxParticipants: int, category: string}>
     */
    public static function buildEventsContext(array $events): array
    {
        $context = [];
        foreach ($events as $event) {
            $context[] = [
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
        return $context;
    }

    /**
     * @param Category[] $categories
     * @return array<int, array{name: string, eventCount: int}>
     */
    public static function buildCategoriesContext(array $categories): array
    {
        $context = [];
        foreach ($categories as $category) {
            $context[] = [
                'name' => $category->getName() ?? '',
                'eventCount' => $category->getEvents()->count(),
            ];
        }
        return $context;
    }
}
