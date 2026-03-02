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

        if (empty($query)) {
            return new JsonResponse(['response' => 'Pose une question sur les utilisateurs !']);
        }

        // Appel à Groq via cURL (simple et fiable)
        $apiKey = $_ENV['GROQ_API_KEY'];
        $url = 'https://api.groq.com/openai/v1/chat/completions';

        $payload = [
            'model' => 'llama-3.3-70b-versatile', // modèle correct et actif
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un assistant admin pour statistiques utilisateurs. Réponds en français de manière naturelle et précise. Utilise les données réelles de la base si possible.'],
                ['role' => 'user', 'content' => $query],
            ],
            'temperature' => 0.7,
            'max_tokens' => 300,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200) {
            $errorMsg = $error ?: 'Code HTTP ' . $httpCode;
            return new JsonResponse(['response' => "Erreur API Groq : $errorMsg"]);
        }

        $json = json_decode($response, true);
        $answer = $json['choices'][0]['message']['content'] ?? 'Désolé, je n\'ai pas compris la question.';

        // Parsing simple pour stats précises (bonus métier)
        $queryLower = strtolower($query);
        if (strpos($queryLower, 'combien d\'utilisateurs') !== false || strpos($queryLower, 'total utilisateurs') !== false) {
            $total = $userRepo->count([]);
            $answer .= "\nTotal utilisateurs : $total.";
        } elseif (strpos($queryLower, 'actifs') !== false) {
            $active = $userRepo->count(['status' => 'active']);
            $answer .= "\nUtilisateurs actifs : $active.";
        } elseif (strpos($queryLower, 'pending') !== false || strpos($queryLower, 'en attente') !== false) {
            $pending = $userRepo->count(['status' => 'pending']);
            $answer .= "\nUtilisateurs en attente : $pending.";
        } elseif (strpos($queryLower, 'rôle') !== false || strpos($queryLower, 'role') !== false) {
            $students = count($userRepo->findByRole('ROLE_STUDENT'));
            $teachers = count($userRepo->findByRole('ROLE_ENSEIGNANT'));
            $admins = count($userRepo->findByRole('ROLE_ADMIN'));
            $answer .= "\nPar rôle : Étudiants : $students, Enseignants : $teachers, Admins : $admins.";
        }

        return new JsonResponse(['response' => $answer]);
    }
}
