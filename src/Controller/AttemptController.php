<?php

namespace App\Controller;

use App\Entity\Attempt;
use App\Repository\AttemptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attempt')]
class AttemptController extends AbstractController
{
    // 1. THE QUIZ PAGE (Where student ticks boxes)
    #[Route('/{id}', name: 'app_quiz_take', methods: ['GET', 'POST'])]
    public function take(Attempt $attempt, Request $request, EntityManagerInterface $em): Response
    {
        // Security: If already finished, don't let them take it again
        if ($attempt->getCompletedAt()) {
            return $this->redirectToRoute('app_quiz_result', ['id' => $attempt->getId()]);
        }

        $quiz = $attempt->getQuiz();

        // HANDLE FORM SUBMISSION
        if ($request->isMethod('POST')) {
            $submittedData = $request->request->all();
            $score = 0;
            $userAnswers = [];

            foreach ($quiz->getQuestions() as $question) {
                // The input name in HTML is "q_15" (q_ + id)
                $inputName = 'q_' . $question->getId();
                
                if (isset($submittedData[$inputName])) {
                    $selectedAnswer = $submittedData[$inputName];
                    $userAnswers[$question->getId()] = $selectedAnswer;

                    // Check if correct
                    if ($selectedAnswer === $question->getCorrectAnswer()) {
                        $score += $question->getPoints();
                    }
                }
            }

            // Save Result
            $attempt->setScore($score);
            $attempt->setAnswers($userAnswers);
            $attempt->setCompletedAt(new \DateTimeImmutable());
            
            $em->flush();

            return $this->redirectToRoute('app_quiz_result', ['id' => $attempt->getId()]);
        }

        return $this->render('quiz/take.html.twig', [
            'attempt' => $attempt,
            'quiz' => $quiz,
            'questions' => $quiz->getQuestions(),
        ]);
    }

    // 2. THE RESULT PAGE (Shows Score)
    #[Route('/{id}/result', name: 'app_quiz_result', methods: ['GET'])]
    public function result(Attempt $attempt): Response
    {
        return $this->render('quiz/result.html.twig', [
            'attempt' => $attempt,
            'quiz' => $attempt->getQuiz(),
        ]);
    }
}