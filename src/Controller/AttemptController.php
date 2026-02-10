<?php

namespace App\Controller;

use App\Entity\Attempt;
use App\Form\AttemptType;
use App\Repository\AttemptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/attempt')]
final class AttemptController extends AbstractController
{
    #[Route(name: 'app_attempt_index', methods: ['GET'])]
    public function index(AttemptRepository $attemptRepository): Response
    {
        return $this->render('attempt/index.html.twig', [
            'attempts' => $attemptRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_attempt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attempt = new Attempt();
        $form = $this->createForm(AttemptType::class, $attempt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attempt);
            $entityManager->flush();

            return $this->redirectToRoute('app_attempt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attempt/new.html.twig', [
            'attempt' => $attempt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attempt_show', methods: ['GET'])]
    public function show(Attempt $attempt): Response
    {
        return $this->render('attempt/show.html.twig', [
            'attempt' => $attempt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attempt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attempt $attempt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttemptType::class, $attempt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attempt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attempt/edit.html.twig', [
            'attempt' => $attempt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attempt_delete', methods: ['POST'])]
    public function delete(Request $request, Attempt $attempt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attempt->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attempt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attempt_index', [], Response::HTTP_SEE_OTHER);
    }
}
