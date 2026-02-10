<?php

namespace App\Controller\Admin;

use App\Entity\Chapitre;
use App\Form\ChapitreType;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;

#[Route('/admin/chapters', name: 'app_admin_chapitre_')]
class AdminChapitreController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ChapitreRepository $chapitreRepository): Response
    {
        return $this->render('admin/chapitre/index.html.twig', [
            'chapitres' => $chapitreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chapitre = new Chapitre();
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitre);
            
            // 1. Save first (optional here, but good practice)
            $entityManager->flush(); 

            // 2. Generate Link to the Parent Course
            // We use the ID of the course this chapter belongs to
            $url = $this->generateUrl('app_cours_show', ['id' => $chapitre->getCours()->getId()]);

            // 3. Create Notification
            $notif = new Notification();
            $notif->setMessage('New Chapter in ' . $chapitre->getCours()->getTitre());
            $notif->setLink($url); // <--- FIXED LINK
            $notif->setIsRead(false);

            $entityManager->persist($notif);
            $entityManager->flush(); // Save the notification

            return $this->redirectToRoute('app_admin_chapitre_index');
        }

        return $this->render('admin/chapitre/new.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_chapitre_index');
        }

        return $this->render('admin/chapitre/edit.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapitre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_chapitre_index');
    }
}