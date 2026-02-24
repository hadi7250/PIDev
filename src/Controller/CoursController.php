<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Chapitre;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/courses', name: 'app_cours_')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'courses' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Cours $cours): Response
    {
        $processedChapters = [];
        foreach ($cours->getChapitres() as $chapter) {
            $processedChapters[] = [
                'id' => $chapter->getId(),
                'titre' => $chapter->getTitre(),
                'contenu' => $this->makeLinksClickable($chapter->getContenu()),
                'aiSummary' => $chapter->getAiSummary() 
            ];
        }

        return $this->render('cours/show.html.twig', [
            'course' => $cours,
            'chapters' => $processedChapters,
        ]);
    }

    #[Route('/{id}/summarize', name: 'summarize', methods: ['POST'])]
    public function summarize(Chapitre $chapter, HttpClientInterface $httpClient, EntityManagerInterface $em): JsonResponse
    {
        $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

        if (!$apiKey) {
            return new JsonResponse(['success' => false, 'message' => 'Clé API absente dans le fichier .env'], 500);
        }

        try {
            // UPDATED: Pointing to the active gemini-2.5-flash model
            $response = $httpClient->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => "Fais un résumé très court et professionnel en français du chapitre : " . $chapter->getTitre()]]]
                    ]
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                return new JsonResponse(['success' => false, 'message' => 'Google API Error ' . $response->getStatusCode()], 500);
            }

            $data = $response->toArray();
            $generatedSummary = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Résumé indisponible.";

            $chapter->setAiSummary($generatedSummary);
            $em->persist($chapter);
            $em->flush();

            return new JsonResponse(['success' => true, 'summary' => $generatedSummary]);
            
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur technique: ' . $e->getMessage()], 500);
        }
    }

    private function makeLinksClickable($text) {
        return preg_replace_callback(
            '/(https?:\/\/[^\s]+)/',
            function ($matches) {
                $url = $matches[1];
                if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([^\s&]+)#', $url, $id)) {
                    $videoId = htmlspecialchars($id[1]);
                    return '<div class="video-responsive"><iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe></div>';
                }
                return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
            },
            $text
        );
    }
}