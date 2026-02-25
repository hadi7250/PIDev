<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Service\ChatService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ChatController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepository,
        private CategoryRepository $categoryRepository,
        private ChatService $chatService,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/chat', name: 'app_chat', methods: ['POST'])]
    public function chat(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        if (!\is_array($data) || empty($data['message'] ?? null)) {
            return new JsonResponse(
                ['error' => 'Missing or invalid "message" in JSON body'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $userMessage = trim((string) $data['message']);
        if ($userMessage === '') {
            return new JsonResponse(
                ['error' => 'Message cannot be empty'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $events = $this->eventRepository->findBy([], ['dateDebut' => 'ASC']);
        $categories = $this->categoryRepository->findAll();

        $eventsContext = ChatService::buildEventsContext($events);
        $categoriesContext = ChatService::buildCategoriesContext($categories);

        try {
            $reply = $this->chatService->ask($userMessage, $eventsContext, $categoriesContext);
        } catch (\Throwable $e) {
            $this->logger->error('Chat API failed: {message}', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            $userMessage = $e->getMessage();
            $isDev = $this->getParameter('kernel.environment') === 'dev';
            $error = $isDev && $userMessage !== ''
                ? 'Chat error: ' . $userMessage
                : 'Chat service temporarily unavailable. Please try again later.';
            return new JsonResponse(
                ['error' => $error],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return new JsonResponse(['reply' => $reply]);
    }
}
