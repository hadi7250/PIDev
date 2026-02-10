<?php

namespace App\Controller\Front;

use App\Entity\Competence;
use App\Entity\Evaluation;
use App\Repository\CompetenceRepository;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student', name: 'student_')]
class StudentController extends AbstractController
{
    #[Route('', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('front/dashboard/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/competence', name: 'competences', methods: ['GET'])]
    public function competences(CompetenceRepository $competenceRepository): Response
    {
        $competences = $competenceRepository->findAll();

        return $this->render('front/competence/index.html.twig', [
            'competences' => $competences,
        ]);
    }

    #[Route('/evaluation', name: 'evaluations', methods: ['GET'])]
    public function evaluations(EvaluationRepository $evaluationRepository): Response
    {
        $evaluations = $evaluationRepository->findAll();

        return $this->render('front/evaluation/index.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }

    #[Route('/overview', name: 'overview', methods: ['GET'])]
    public function overview(): Response
    {
        return $this->render('front/overview/index.html.twig');
    }

    #[Route('/my-evaluations', name: 'my_evaluations', methods: ['GET'])]
    public function myEvaluations(): Response
    {
        return $this->render('front/my_evaluations/index.html.twig');
    }

    #[Route('/my-progress', name: 'my_progress', methods: ['GET'])]
    public function myProgress(): Response
    {
        return $this->render('front/my_progress/index.html.twig');
    }

    #[Route('/calendar', name: 'calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        return $this->render('front/calendar/index.html.twig');
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(): Response
    {
        return $this->render('front/profile/index.html.twig');
    }
}