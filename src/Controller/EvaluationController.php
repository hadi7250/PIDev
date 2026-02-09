<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evaluation')]
class EvaluationController extends AbstractController
{
    #[Route('', name: 'evaluation_index', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'evaluation_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();

            $this->addFlash('success', 'Évaluation créée avec succès !');
            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'evaluation_show', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'evaluation_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function edit(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Évaluation modifiée avec succès !');
            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'evaluation_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_TEACHER')]
    public function delete(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evaluation);
            $entityManager->flush();
            $this->addFlash('success', 'Évaluation supprimée avec succès !');
        }

        return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'evaluation_search', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function search(Request $request, EvaluationRepository $evaluationRepository): Response
    {
        $keyword = $request->query->get('q', '');
        $type = $request->query->get('type');
        
        $evaluations = $evaluationRepository->search($keyword, $type);
        
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluations,
            'keyword' => $keyword,
            'selectedType' => $type,
        ]);
    }
}
