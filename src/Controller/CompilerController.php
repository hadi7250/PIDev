<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\EvaluationExercise;
use App\Repository\EvaluationExerciseRepository;
use App\Repository\EvaluationRepository;
use App\Service\CodeCompiler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/compiler', name: 'compiler_')]
class CompilerController extends AbstractController
{
    public function __construct(
        private CodeCompiler $codeCompiler,
        private EvaluationRepository $evaluationRepo,
        private EvaluationExerciseRepository $exerciseRepo,
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route('/evaluate/{id}', name: 'evaluate', methods: ['GET'])]
    public function compilerView(int $id): Response
    {
        $evaluation = $this->evaluationRepo->find($id);
        if (!$evaluation) {
            throw $this->createNotFoundException('Evaluation not found');
        }

        $exercises = $evaluation->getExercises();
        $language = $evaluation->getLanguage() ?? 'python';
        $supportedLanguages = $this->codeCompiler->getSupportedLanguages();

        return $this->render('compiler/index.html.twig', [
            'evaluation' => $evaluation,
            'exercises' => $exercises,
            'language' => $language,
            'supportedLanguages' => $supportedLanguages,
        ]);
    }

    #[Route('/exercise/{exerciseId}', name: 'exercise', methods: ['GET'])]
    public function getExercise(int $exerciseId): Response
    {
        $exercise = $this->exerciseRepo->find($exerciseId);
        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $blanks = $this->codeCompiler->extractBlanks($exercise->getTemplateCode());

        return $this->render('compiler/exercise.html.twig', [
            'exercise' => $exercise,
            'blanks' => $blanks,
        ]);
    }

    #[Route('/compile', name: 'compile', methods: ['POST'])]
    public function compile(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? '';
        $language = $data['language'] ?? 'python';

        $result = $this->codeCompiler->compile($code, $language);

        return $this->json($result);
    }

    #[Route('/validate-solution', name: 'validate_solution', methods: ['POST'])]
    public function validateSolution(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $filledCode = $data['code'] ?? '';
        $exerciseId = $data['exerciseId'] ?? null;

        if (!$exerciseId) {
            return $this->json(['error' => 'Exercise ID is required'], 400);
        }

        $exercise = $this->exerciseRepo->find($exerciseId);
        if (!$exercise) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        $result = $this->codeCompiler->validateSolution(
            $filledCode,
            $exercise->getSolutionCode(),
            $exercise->getLanguage()
        );

        return $this->json($result);
    }

    #[Route('/create-exercise/{evaluationId}', name: 'create_exercise', methods: ['GET', 'POST'])]
    public function createExercise(Request $request, int $evaluationId): Response
    {
        $evaluation = $this->evaluationRepo->find($evaluationId);
        if (!$evaluation) {
            throw $this->createNotFoundException('Evaluation not found');
        }

        if ($request->isMethod('POST')) {
            $exercise = new EvaluationExercise();
            $exercise->setEvaluation($evaluation);
            $exercise->setLanguage($request->request->get('language') ?? $evaluation->getLanguage());
            $exercise->setTemplateCode($request->request->get('templateCode'));
            $exercise->setSolutionCode($request->request->get('solutionCode'));
            $exercise->setDescription($request->request->get('description'));
            $exercise->setHint($request->request->get('hint'));
            $exercise->setDifficulty((int)($request->request->get('difficulty') ?? 1));

            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            return $this->redirectToRoute('compiler_exercise', ['exerciseId' => $exercise->getId()]);
        }

        $supportedLanguages = $this->codeCompiler->getSupportedLanguages();

        return $this->render('compiler/create_exercise.html.twig', [
            'evaluation' => $evaluation,
            'supportedLanguages' => $supportedLanguages,
        ]);
    }

    #[Route('/extract-blanks', name: 'extract_blanks', methods: ['POST'])]
    public function extractBlanks(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $templateCode = $data['code'] ?? '';

        $result = $this->codeCompiler->extractBlanks($templateCode);

        return $this->json($result);
    }
}
