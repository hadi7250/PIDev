<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/chatbot')]
class ChatBotController extends AbstractController
{
    #[Route('/query', name: 'admin_chatbot_query', methods: ['POST'])]
    public function query(Request $request, UserRepository $userRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $query = trim($data['query'] ?? '');

        $answer = 'Désolé, je n\'ai pas compris la question. Essayez : "Combien d\'utilisateurs ?", "Utilisateurs actifs ?", "Stats par rôle ?"';

        // Détection mots-clés + vraies stats DB
        $queryLower = strtolower(trim($query));

        if (str_contains($queryLower, 'combien d\'utilisateurs') || str_contains($queryLower, 'how many users') || str_contains($queryLower, 'total users')) {
            $total = $userRepo->count([]);
            $answer = "Il y a actuellement **$total** utilisateurs dans la base de données.";
        }

        elseif (str_contains($queryLower, 'actifs') || str_contains($queryLower, 'active') || str_contains($queryLower, 'active users')) {
            $active = $userRepo->count(['status' => 'active']);
            $answer = "Il y a **$active** utilisateurs actifs.";
        }

        elseif (str_contains($queryLower, 'pending') || str_contains($queryLower, 'en attente')) {
            $pending = $userRepo->count(['status' => 'pending']);
            $answer = "Il y a **$pending** utilisateurs en attente d'approbation.";
        }

        elseif (str_contains($queryLower, 'rôle') || str_contains($queryLower, 'role') || str_contains($queryLower, 'stats par rôle')) {
            $students = count($userRepo->findByRole('ROLE_STUDENT'));
            $teachers = count($userRepo->findByRole('ROLE_ENSEIGNANT'));
            $admins = count($userRepo->findByRole('ROLE_ADMIN'));
            $answer = "Stats par rôle : Étudiants = $students, Enseignants = $teachers, Admins = $admins.";
        }

        elseif (str_contains($queryLower, 'student') || str_contains($queryLower, 'étudiant') || str_contains($queryLower, 'étudiants') || str_contains($queryLower, 'students')) {
            $students = count($userRepo->findByRole('ROLE_STUDENT'));
            $answer = "Il y a **$students** étudiants (ROLE_STUDENT) dans la base de données.";
        }

        elseif (str_contains($queryLower, 'teacher') || str_contains($queryLower, 'enseignant') || str_contains($queryLower, 'enseignants') || str_contains($queryLower, 'teachers')) {
            $teachers = count($userRepo->findByRole('ROLE_ENSEIGNANT'));
            $answer = "Il y a **$teachers** enseignants (ROLE_ENSEIGNANT) dans la base de données.";
        }

        elseif (str_contains($queryLower, 'admin') || str_contains($queryLower, 'admins') || str_contains($queryLower, 'administrateur') || str_contains($queryLower, 'administrateurs')) {
            $admins = count($userRepo->findByRole('ROLE_ADMIN'));
            $answer = "Il y a **$admins** administrateurs (ROLE_ADMIN) dans la base de données.";
        }

        elseif (str_contains($queryLower, 'photo') || str_contains($queryLower, 'picture') || str_contains($queryLower, 'avatar') || str_contains($queryLower, 'photos')) {
            $withPhoto = $userRepo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.photo IS NOT NULL')
                ->getQuery()
                ->getSingleScalarResult();
            $answer = "Il y a **$withPhoto** utilisateurs avec une photo de profil.";
        }

        elseif (str_contains($queryLower, 'nsc') || str_contains($queryLower, 'numéro étudiant') || str_contains($queryLower, 'student card')) {
            $withNsc = $userRepo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.nsc IS NOT NULL')
                ->getQuery()
                ->getSingleScalarResult();
            $answer = "Il y a **$withNsc** utilisateurs avec un NSC renseigné.";
        }

        return new JsonResponse(['response' => $answer]);
    }
}
