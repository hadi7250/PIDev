<?php

namespace App\Controller\Back;

use App\Entity\Competence;
use App\Entity\Evaluation;
use App\Repository\CompetenceRepository;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(
        CompetenceRepository $competenceRepository,
        EvaluationRepository $evaluationRepository
    ): Response {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_DEVELOPER');

        if ($isAdmin) {
            $totalCompetences = $competenceRepository->count([]);
            $totalEvaluations = $evaluationRepository->count([]);
        } else {
            $totalCompetences = $competenceRepository->count([]);
            $totalEvaluations = count($user->getEvaluationsCreated());
        }

        return $this->render('back/dashboard/index.html.twig', [
            'totalCompetences' => $totalCompetences,
            'totalEvaluations' => $totalEvaluations,
            'isAdmin' => $isAdmin,
            'user' => $user,
        ]);
    }

    #[Route('/competences', name: 'competences')]
    public function competences(CompetenceRepository $competenceRepository): Response
    {
        $competences = $competenceRepository->findAll();

        return $this->render('back/competence/index.html.twig', [
            'competences' => $competences,
        ]);
    }

    #[Route('/evaluations', name: 'evaluations')]
    public function evaluations(EvaluationRepository $evaluationRepository): Response
    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_DEVELOPER');

        if ($isAdmin) {
            $evaluations = $evaluationRepository->findAll();
        } else {
            $evaluations = $user->getEvaluationsCreated()->toArray();
        }

        return $this->render('back/evaluation/index.html.twig', [
            'evaluations' => $evaluations,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/grade-students', name: 'grade_students')]
    public function gradeStudents(EvaluationRepository $evaluationRepository): Response
    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_DEVELOPER');

        if ($isAdmin) {
            $evaluations = $evaluationRepository->findAll();
        } else {
            $evaluations = $user->getEvaluationsCreated()->toArray();
        }

        return $this->render('back/evaluation/grade_students.html.twig', [
            'evaluations' => $evaluations,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/users', name: 'users')]
    public function users(): Response
    {
        return $this->render('back/user/index.html.twig', []);
    }

    #[Route('/reports', name: 'reports')]
    public function reports(
        CompetenceRepository $competenceRepository,
        EvaluationRepository $evaluationRepository
    ): Response {
        $totalCompetences = $competenceRepository->count([]);
        $totalEvaluations = $evaluationRepository->count([]);

        return $this->render('back/reports/index.html.twig', [
            'totalCompetences' => $totalCompetences,
            'totalEvaluations' => $totalEvaluations,
        ]);
    }
}
