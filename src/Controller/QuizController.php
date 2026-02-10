<?php

namespace App\Controller;

use App\Entity\Attempt;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    // 1. LIST QUIZZES (Points to your specific file: front/quiz_list.html.twig)
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('front/quiz_list.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    // 2. SHOW DETAILS
    #[Route('/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    // 3. START QUIZ (Creates Attempt, Redirects to Take)
    #[Route('/{id}/start', name: 'app_quiz_start', methods: ['POST'])]
    public function start(Quiz $quiz, EntityManagerInterface $em): Response
    {
        $attempt = new Attempt();
        $attempt->setQuiz($quiz);
        // No student name required anymore

        $em->persist($attempt);
        $em->flush();

        return $this->redirectToRoute('app_quiz_take', ['id' => $attempt->getId()]);
    }

    // 4. TAKE QUIZ (Questions & Timer)
    #[Route('/attempt/{id}', name: 'app_quiz_take', methods: ['GET', 'POST'])]
    public function take(Attempt $attempt, Request $request, EntityManagerInterface $em): Response
    {
        if ($attempt->getCompletedAt()) {
            return $this->redirectToRoute('app_quiz_result', ['id' => $attempt->getId()]);
        }

        $quiz = $attempt->getQuiz();

        if ($request->isMethod('POST')) {
            $submittedAnswers = $request->request->all();
            $score = 0;
            
            foreach ($quiz->getQuestions() as $question) {
                $inputName = 'q_' . $question->getId();
                if (isset($submittedAnswers[$inputName])) {
                    if ($submittedAnswers[$inputName] === $question->getCorrectAnswer()) {
                        $score += $question->getPoints();
                    }
                }
            }

            $attempt->setScore($score);
            $attempt->setAnswers($submittedAnswers);
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

    // 5. RESULT PAGE
    #[Route('/result/{id}', name: 'app_quiz_result', methods: ['GET'])]
    public function result(Attempt $attempt): Response
    {
        return $this->render('quiz/result.html.twig', [
            'attempt' => $attempt,
            'quiz' => $attempt->getQuiz(),
        ]);
    }
}