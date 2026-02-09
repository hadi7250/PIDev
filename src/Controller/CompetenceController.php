<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/competence')]
class CompetenceController extends AbstractController
{
    #[Route('', name: 'competence_index', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function index(Request $request, CompetenceRepository $competenceRepository): Response
    {
        $keyword = $request->query->get('keyword', '');
        $category = $request->query->get('category');
        
        if ($keyword || $category) {
            $competences = $competenceRepository->search($keyword, $category);
        } else {
            $competences = $competenceRepository->findAll();
        }
        
        return $this->render('competence/index.html.twig', [
            'competences' => $competences,
        ]);
    }

    #[Route('/new', name: 'competence_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($competence);
            $entityManager->flush();

            $this->addFlash('success', 'Compétence créée avec succès !');
            return $this->redirectToRoute('competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('competence/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'competence_search', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function search(Request $request, CompetenceRepository $competenceRepository): Response
    {
        $keyword = $request->query->get('q', '');
        $category = $request->query->get('category');
        
        $competences = $competenceRepository->search($keyword, $category);
        
        return $this->render('competence/index.html.twig', [
            'competences' => $competences,
            'keyword' => $keyword,
            'selectedCategory' => $category,
        ]);
    }

    #[Route('/export', name: 'competence_export', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function export(CompetenceRepository $competenceRepository): Response
    {
        $competences = $competenceRepository->findAll();
        
        $csvData = "ID;Nom;Catégorie;Niveau Max;Description\n";
        
        foreach ($competences as $competence) {
            $csvData .= sprintf(
                "%d;%s;%s;%d;%s\n",
                $competence->getId(),
                $competence->getName(),
                $competence->getCategory(),
                $competence->getMaxLevel(),
                str_replace(';', ',', $competence->getDescription() ?? '')
            );
        }
        
        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 
            'attachment; filename="competences_' . date('Y-m-d') . '.csv"');
        
        return $response;
    }

    #[Route('/{id}', name: 'competence_show', methods: ['GET'])]
    #[IsGranted('ROLE_STUDENT')]
    public function show(Competence $competence): Response
    {
        return $this->render('competence/show.html.twig', [
            'competence' => $competence,
        ]);
    }

    #[Route('/{id}/edit', name: 'competence_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Competence $competence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Compétence modifiée avec succès !');
            return $this->redirectToRoute('competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('competence/edit.html.twig', [
            'competence' => $competence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'competence_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Competence $competence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $competence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($competence);
            $entityManager->flush();
            $this->addFlash('success', 'Compétence supprimée avec succès !');
        }

        return $this->redirectToRoute('competence_index', [], Response::HTTP_SEE_OTHER);
    }
}
