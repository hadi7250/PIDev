<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/questions')]
class AdminQuestionController extends AbstractController
{
    #[Route('/{id}', name: 'app_admin_question_index', methods: ['GET', 'POST'])]
    public function index(Quiz $quiz, Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Get the 4 separate options from the form
            $options = [
                $form->get('option1')->getData(),
                $form->get('option2')->getData(),
                $form->get('option3')->getData(),
                $form->get('option4')->getData(),
            ];

            // 2. Save them into the Entity as an array
            $question->setOptions($options);

            // 3. Set the Correct Answer text based on the choice (0, 1, 2, or 3)
            $correctIndex = $form->get('correctAnswerChoice')->getData();
            $question->setCorrectAnswer($options[$correctIndex]);

            // 4. Link Question to the Quiz
            $question->setQuiz($quiz);

            $entityManager->persist($question);
            $entityManager->flush();

            // Reload page to show new question
            return $this->redirectToRoute('app_admin_question_index', ['id' => $quiz->getId()]);
        }

        return $this->render('back/questions.html.twig', [
            'quiz' => $quiz,
            'questions' => $quiz->getQuestions(), // Assuming OneToMany exists in Quiz
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $quizId = $question->getQuiz()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_question_index', ['id' => $quizId]);
    }

    #[Route('/{id}/edit', name: 'app_admin_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);

        // 1. PRE-FILL THE FORM (Unpack the array options back into fields)
        $currentOptions = $question->getOptions();
        if (count($currentOptions) >= 4) {
            $form->get('option1')->setData($currentOptions[0]);
            $form->get('option2')->setData($currentOptions[1]);
            $form->get('option3')->setData($currentOptions[2]);
            $form->get('option4')->setData($currentOptions[3]);

            // Find which index (0-3) matches the stored correct answer string
            $correctIndex = array_search($question->getCorrectAnswer(), $currentOptions);
            if ($correctIndex !== false) {
                $form->get('correctAnswerChoice')->setData($correctIndex);
            }
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 2. SAVE CHANGES (Pack fields back into the array)
            $newOptions = [
                $form->get('option1')->getData(),
                $form->get('option2')->getData(),
                $form->get('option3')->getData(),
                $form->get('option4')->getData(),
            ];

            $question->setOptions($newOptions);

            // Update correct answer text based on the chosen index
            $correctIndex = $form->get('correctAnswerChoice')->getData();
            $question->setCorrectAnswer($newOptions[$correctIndex]);

            $entityManager->flush();

            // Redirect back to the question list for this Quiz
            return $this->redirectToRoute('app_admin_question_index', ['id' => $question->getQuiz()->getId()]);
        }

        return $this->render('back/question_edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
}