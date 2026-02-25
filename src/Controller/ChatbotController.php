<?php

namespace App\Controller;

use App\Entity\ChatMessage;
use App\Entity\Evaluation;
use App\Entity\EvaluationExercise;
use App\Repository\ChatMessageRepository;
use App\Service\CodeQuestionGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chatbot')]
class ChatbotController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChatMessageRepository $chatMessageRepository,
        private CodeQuestionGenerator $questionGenerator
    ) {}

    #[Route('', name: 'app_chatbot', methods: ['GET'])]
    public function index(): Response
    {
        $recentMessages = $this->chatMessageRepository->findRecent(20);
        
        // Process messages to extract exercise title for display
        $processedMessages = [];
        foreach ($recentMessages as $message) {
            $responseData = json_decode($message->getBotResponse(), true);
            $processedMessages[] = [
                'id' => $message->getId(),
                'language' => $message->getLanguage(),
                'userMessage' => $message->getUserMessage(),
                'botResponse' => $message->getBotResponse(),
                'exerciseTitle' => $responseData['title'] ?? 'N/A',
                'createdAt' => $message->getCreatedAt()
            ];
        }
        
        // Get evaluations that have a compiler (those with a language set)
        $evaluations = $this->entityManager->getRepository(Evaluation::class)
            ->createQueryBuilder('e')
            ->where('e.language IS NOT NULL')
            ->andWhere('e.language != :empty')
            ->setParameter('empty', '')
            ->orderBy('e.title', 'ASC')
            ->getQuery()
            ->getResult();
        
        return $this->render('chatbot/index.html.twig', [
            'recent_messages' => $processedMessages,
            'languages' => [
                'PHP' => 'PHP',
                'Python' => 'Python',
                'JavaScript' => 'JavaScript',
                'Java' => 'Java',
                'C++' => 'C++',
                'C#' => 'C#',
                'Go' => 'Go',
                'Rust' => 'Rust',
            ],
            'evaluations' => $evaluations
        ]);
    }

    #[Route('/ask', name: 'app_chatbot_ask', methods: ['POST'])]
    public function ask(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['prompt']) || !isset($data['language'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Missing prompt or language'
                ], Response::HTTP_BAD_REQUEST);
            }

            $prompt = trim($data['prompt']);
            $language = trim($data['language']);

            if (empty($prompt)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Prompt cannot be empty'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Call AI to generate question
            $generatedExercise = $this->questionGenerator->generateQuestion($prompt, $language);

            // Store conversation in database
            $chatMessage = new ChatMessage();
            $chatMessage->setUserMessage($prompt);
            $chatMessage->setBotResponse(json_encode($generatedExercise));
            $chatMessage->setLanguage($language);
            $chatMessage->setCreatedAt(new \DateTime());

            $this->entityManager->persist($chatMessage);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'exercise' => $generatedExercise,
                'message_id' => $chatMessage->getId()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/history', name: 'app_chatbot_history', methods: ['GET'])]
    public function history(Request $request): JsonResponse
    {
        try {
            $language = $request->query->get('language');
            $limit = min((int)$request->query->get('limit', 10), 50);

            $messages = $this->chatMessageRepository->findRecent($limit, $language);

            $result = [];
            foreach ($messages as $message) {
                $result[] = [
                    'id' => $message->getId(),
                    'user_message' => $message->getUserMessage(),
                    'bot_response' => json_decode($message->getBotResponse(), true),
                    'language' => $message->getLanguage(),
                    'created_at' => $message->getCreatedAt()->format('c')
                ];
            }

            return new JsonResponse([
                'success' => true,
                'messages' => $result
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/save-exercise/{messageId}', name: 'app_chatbot_save_exercise', methods: ['POST'])]
    public function saveExercise(int $messageId, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['evaluation_id'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Missing evaluation_id'
                ], Response::HTTP_BAD_REQUEST);
            }

            $evaluation = $this->entityManager->getRepository(Evaluation::class)
                ->find($data['evaluation_id']);
            
            if (!$evaluation) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Evaluation not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $chatMessage = $this->chatMessageRepository->find($messageId);
            if (!$chatMessage) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Chat message not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $exercise = json_decode($chatMessage->getBotResponse(), true);

            // Create EvaluationExercise entity
            $evaluationExercise = new EvaluationExercise();
            $evaluationExercise->setEvaluation($evaluation);
            $evaluationExercise->setTitle($exercise['title'] ?? 'Generated Exercise');
            $evaluationExercise->setDescription($exercise['description'] ?? '');
            $evaluationExercise->setTemplateCode($exercise['template']);
            $evaluationExercise->setSolutionCode($exercise['solution']);
            $evaluationExercise->setLanguage($exercise['language']);
            $evaluationExercise->setDifficulty($exercise['difficulty']);
            $evaluationExercise->setHints($exercise['hints']);
            $evaluationExercise->setCreatedAt(new \DateTime());

            $this->entityManager->persist($evaluationExercise);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'exercise_id' => $evaluationExercise->getId(),
                'message' => 'Exercise saved successfully'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
