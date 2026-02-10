<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/quiz', name: 'app_admin_quiz_')]
class AdminQuizController extends AbstractController
{
    #[Route('/', name: 'datatable', methods: ['GET', 'POST'])]
    public function index(Request $request, QuizRepository $quizRepository, EntityManagerInterface $entityManager): Response
    {
        // 1. Handle "Add New Quiz" Form (Modal)
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            $this->addFlash('success', 'Quiz created successfully!');
            return $this->redirectToRoute('app_admin_quiz_datatable');
        }

        // 2. Render the View
        return $this->render('admin/quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Quiz updated successfully!');
            return $this->redirectToRoute('app_admin_quiz_datatable');
        }

        return $this->render('admin/quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
            $this->addFlash('success', 'Quiz deleted successfully!');
        }

        return $this->redirectToRoute('app_admin_quiz_datatable');
    }
}