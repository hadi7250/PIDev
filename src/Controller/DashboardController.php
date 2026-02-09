<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CompetenceRepository $competenceRepo, 
                         EvaluationRepository $evaluationRepo): Response
    {
        $competenceCount = $competenceRepo->count([]);
        $evaluationCount = $evaluationRepo->count([]);
        
        // Group by category
        $competences = $competenceRepo->findAll();
        $categories = [];
        foreach ($competences as $competence) {
            $cat = $competence->getCategory();
            $categories[$cat] = ($categories[$cat] ?? 0) + 1;
        }
        
        // Recent evaluations
        $recentEvaluations = $evaluationRepo->createQueryBuilder('e')
            ->orderBy('e.evaluationDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        
        return $this->render('dashboard/index.html.twig', [
            'competenceCount' => $competenceCount,
            'evaluationCount' => $evaluationCount,
            'competencesByCategory' => $categories,
            'recentEvaluations' => $recentEvaluations,
        ]);
    }
}
