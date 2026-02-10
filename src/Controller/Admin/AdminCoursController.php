<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;

#[Route('/admin/courses', name: 'app_admin_cours_')]
class AdminCoursController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('admin/cours/index.html.twig', [
            'courses' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cours = new Cours();
        $cours->setCreatedAt(new \DateTime());
        
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            
            // 1. SAVE FIRST so the Course gets an ID
            $entityManager->flush(); 
            
            // 2. Generate the correct URL (e.g., /courses/15)
            $url = $this->generateUrl('app_cours_show', ['id' => $cours->getId()]);

            // 3. Create Notification with the correct link
            $notif = new Notification();
            $notif->setMessage('New Course Added: ' . $cours->getTitre());
            $notif->setLink($url); // <--- FIXED LINK
            $notif->setIsRead(false); // Explicitly set unread
            
            $entityManager->persist($notif);
            $entityManager->flush(); // Save the notification

            return $this->redirectToRoute('app_admin_cours_index');
        }

        return $this->render('admin/cours/new.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_cours_index');
        }

        return $this->render('admin/cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cours->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cours);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_cours_index');
    }
}