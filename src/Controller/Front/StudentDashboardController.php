<?php

namespace App\Controller\Front;

use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student', name: 'student_')]
class StudentDashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(EvaluationRepository $evaluationRepository): Response
    {
        $user = $this->getUser();
        $totalEvaluations = $evaluationRepository->count([]);
        $completedEvaluations = 0;
        $pendingEvaluations = 0;
        $averageScore = 0;

        return $this->render('front/dashboard/index.html.twig', [
            'totalEvaluations' => $totalEvaluations,
            'completedEvaluations' => $completedEvaluations,
            'pendingEvaluations' => $pendingEvaluations,
            'averageScore' => $averageScore,
            'user' => $user,
        ]);
    }

    #[Route('/my-evaluations', name: 'my_evaluations')]
    public function myEvaluations(EvaluationRepository $evaluationRepository): Response
    {
        $user = $this->getUser();
        $evaluations = $evaluationRepository->findAll();

        return $this->render('front/evaluation/my_evaluations.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }

    #[Route('/my-progress', name: 'my_progress')]
    public function myProgress(): Response
    {
        $user = $this->getUser();

        return $this->render('front/progress/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/calendar', name: 'calendar')]
    public function calendar(EvaluationRepository $evaluationRepository): Response
    {
        $user = $this->getUser();
        $evaluations = $evaluationRepository->findAll();

        return $this->render('front/calendar/index.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('front/profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
