<?php

namespace App\Controller;

use App\Repository\AttemptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/attempts', name: 'app_admin_attempt_')]
class AdminAttemptController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AttemptRepository $attemptRepository): Response
    {
        return $this->render('admin/attempt/index.html.twig', [
            'attempts' => $attemptRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, $id, AttemptRepository $repo, EntityManagerInterface $em): Response
    {
        $attempt = $repo->find($id);
        if ($attempt && $this->isCsrfTokenValid('delete'.$attempt->getId(), $request->request->get('_token'))) {
            $em->remove($attempt);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_attempt_index');
    }
}