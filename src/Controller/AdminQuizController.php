<?php

namespace App\Controller;

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
    // 1. DASHBOARD: Points to the main Maxton Dashboard
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('back/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    // 2. DATATABLE + CREATE MODAL: This is your main management page
    #[Route('/datatable', name: 'datatable', methods: ['GET', 'POST'])]
    public function datatable(Request $request, QuizRepository $quizRepository, EntityManagerInterface $entityManager): Response
    {
        // Create new Quiz object for the Modal Form
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        // Handle Form Submission (Create Quiz)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);


            $entityManager->flush();

            // Reload page to see new quiz
            return $this->redirectToRoute('app_admin_quiz_datatable');
        }

        return $this->render('back/datatable.html.twig', [
            'quizzes' => $quizRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    // 3. EDIT PAGE: Standard edit route
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_quiz_datatable');
        }

        // CHANGE THIS LINE to your desired path:
        return $this->render('back/edit_quiz.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    // 4. DELETE ACTION
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_quiz_datatable');
    }

    // 5. NEW PAGE (Optional backup if Modal fails)
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_quiz_datatable');
        }

        return $this->render('admin/quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }
}