<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/question', name: 'app_admin_question_')]
class AdminQuestionController extends AbstractController
{
    #[Route('/manage/{id}', name: 'index', methods: ['GET', 'POST'])]
    public function index(Quiz $quiz, Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setQuiz($quiz);
        
        // Setup default options for form if needed
        $question->setOptions(['', '', '', '']); 

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Filter out empty options
            $cleanOptions = array_values(array_filter($question->getOptions()));
            $question->setOptions($cleanOptions);

            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question added!');
            return $this->redirectToRoute('app_admin_question_index', ['id' => $quiz->getId()]);
        }

        return $this->render('admin/question/index.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_question_index', ['id' => $question->getQuiz()->getId()]);
        }

        return $this->render('admin/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $quizId = $question->getQuiz()->getId();
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_question_index', ['id' => $quizId]);
    }
}