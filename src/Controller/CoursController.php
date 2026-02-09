<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        // We prepare the chapters with processed content (clickable links/embeds)
        $processedChapters = [];
        
        foreach ($cours->getChapitres() as $chapter) {
            $processedChapters[] = [
                'id' => $chapter->getId(),
                'titre' => $chapter->getTitre(),
                'contenu' => $this->makeLinksClickable($chapter->getContenu())
            ];
        }

        return $this->render('cours/show.html.twig', [
            'course' => $cours,
            'chapters' => $processedChapters,
        ]);
    }

    // This is your custom function converted to a private method
    private function makeLinksClickable($text) {
        return preg_replace_callback(
            '/(https?:\/\/[^\s]+)/',
            function ($matches) {
                $url = $matches[1];

                // YouTube
                if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([^\s&]+)#', $url, $id)) {
                    $videoId = htmlspecialchars($id[1]);
                    return '<div class="video-responsive"><iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe></div>';
                }

                // Instagram
                if (strpos($url, 'instagram.com') !== false) {
                    return '<blockquote class="instagram-media" data-instgrm-permalink="' . $url . '" data-instgrm-version="14"></blockquote>';
                }

                // Twitter
                if (strpos($url, 'twitter.com') !== false) {
                    return '<blockquote class="twitter-tweet"><a href="' . $url . '"></a></blockquote>';
                }

                // Twitch
                if (preg_match('#twitch\.tv/([^/]+)(/v/(\d+)|/videos/(\d+))?#', $url, $matches)) {
                    $videoId = !empty($matches[3]) ? $matches[3] : ($matches[4] ?? '');
                    $channel = htmlspecialchars($matches[1]);
                    if ($videoId) {
                        return '<iframe src="https://player.twitch.tv/?video=v' . $videoId . '&parent=localhost" frameborder="0" allowfullscreen></iframe>';
                    } else {
                        return '<iframe src="https://player.twitch.tv/?channel=' . $channel . '&parent=localhost" frameborder="0" allowfullscreen></iframe>';
                    }
                }

                return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
            },
            $text
        );
    }
}