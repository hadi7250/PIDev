<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Génère une synthèse de rapport back-office via IA (Gemini en priorité, puis Grok, puis OpenAI).
 * @see https://ai.google.dev/gemini-api/docs
 */
final class ReportService
{
    private const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';
    private const GROK_API_URL = 'https://api.x.ai/v1/chat/completions';
    private const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $grokApiKey,
        private string $openaiApiKey,
        private string $geminiApiKey,
        private ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * Génère un paragraphe de synthèse en français à partir des statistiques du back-office.
     *
     * @param array{events_count: int, categories_count: int, participations_count: int, ratings_count: int, certificats_count: int, events_by_category: array<string, int>} $stats
     */
    public function generateSummary(array $stats): string
    {
        $statsJson = json_encode($stats, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE);

        $systemPrompt = <<<PROMPT
Tu es un expert en analyse de données pour des rapports de gestion. Tu rédiges des ANALYSES (interprétation, tendances, recommandations), pas seulement un résumé des chiffres.
Réponds UNIQUEMENT en français, en deux ou trois paragraphes (6 à 12 phrases).
Inclus : (1) une interprétation des chiffres (ce qu'ils indiquent), (2) les points forts et faibles, (3) des recommandations concrètes pour améliorer l'engagement ou l'activité. Reste factuel, n'invente pas de données.
PROMPT;

        $userMessage = <<<MSG
Voici les statistiques du back-office d'une plateforme de gestion d'événements. Rédige une analyse professionnelle pour un rapport PDF (interprétation, tendances, recommandations).

Statistiques :
{$statsJson}
MSG;

        $geminiKey = trim((string) $this->geminiApiKey);
        if ($geminiKey !== '') {
            $result = $this->callGemini($geminiKey, $systemPrompt, $userMessage, $stats);
            if ($result !== null) {
                return $result;
            }
        }

        $grokKey = trim((string) $this->grokApiKey);
        if ($grokKey !== '') {
            foreach (['grok-2', 'grok-4-1-fast-reasoning', 'grok-beta'] as $model) {
                $result = $this->callGrokApi($grokKey, $model, $systemPrompt, $userMessage, $stats);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        $openaiKey = trim((string) $this->openaiApiKey);
        if ($openaiKey !== '' && !str_starts_with($openaiKey, 'your_openai')) {
            $result = $this->callOpenAi($openaiKey, $systemPrompt, $userMessage, $stats);
            if ($result !== null) {
                return $result;
            }
        }

        return $this->buildFallbackSummary($stats);
    }

    /**
     * Gemini (Google AI) en priorité.
     * @param array{events_count: int, categories_count: int, participations_count: int, ratings_count: int, certificats_count: int, events_by_category: array<string, int>} $stats
     */
    private function callGemini(string $key, string $systemPrompt, string $userMessage, array $stats): ?string
    {
        $fullPrompt = $systemPrompt . "\n\n" . $userMessage;
        $modelsToTry = ['gemini-1.5-flash', 'gemini-2.0-flash', 'gemini-1.5-pro', 'gemini-2.0-flash-exp'];

        foreach ($modelsToTry as $model) {
            $url = sprintf(self::GEMINI_API_URL, $model);
            $payload = [
                'contents' => [
                    [
                        'parts' => [['text' => $fullPrompt]],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 600,
                    'temperature' => 0.4,
                ],
            ];

            try {
                $response = $this->httpClient->request('POST', $url . '?key=' . urlencode($key), [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $payload,
                    'timeout' => 60,
                ]);

                $status = $response->getStatusCode();
                $body = $response->getContent(false);

                if ($status < 200 || $status >= 300) {
                    $this->logger?->error('ReportService Gemini', ['model' => $model, 'status' => $status, 'body' => substr($body, 0, 500)]);
                    continue;
                }

                $data = json_decode($body, true);
                if (!\is_array($data)) {
                    continue;
                }
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text !== null && $text !== '') {
                    return trim((string) $text);
                }
            } catch (\Throwable $e) {
                $this->logger?->error('ReportService Gemini (' . $model . '): ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Repli sur OpenAI si Grok échoue.
     * @param array{events_count: int, categories_count: int, participations_count: int, ratings_count: int, certificats_count: int, events_by_category: array<string, int>} $stats
     */
    private function callOpenAi(string $key, string $systemPrompt, string $userMessage, array $stats): ?string
    {
        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 600,
            'temperature' => 0.4,
        ];

        try {
            $response = $this->httpClient->request('POST', self::OPENAI_API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $key,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 60,
            ]);

            $status = $response->getStatusCode();
            $body = $response->getContent(false);

            if ($status < 200 || $status >= 300) {
                $this->logger?->error('ReportService OpenAI fallback', ['status' => $status, 'body' => $body]);
                return null;
            }

            $data = json_decode($body, true);
            if (!\is_array($data)) {
                return null;
            }
            $content = $data['choices'][0]['message']['content'] ?? null;
            if ($content !== null && $content !== '') {
                return trim((string) $content);
            }
            return null;
        } catch (\Throwable $e) {
            $this->logger?->error('ReportService OpenAI fallback: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param array{events_count: int, categories_count: int, participations_count: int, ratings_count: int, certificats_count: int, events_by_category: array<string, int>} $stats
     */
    private function callGrokApi(string $key, string $model, string $systemPrompt, string $userMessage, array $stats): ?string
    {
        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 600,
            'temperature' => 0.4,
        ];

        try {
            $response = $this->httpClient->request('POST', self::GROK_API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $key,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 90,
            ]);

            $status = $response->getStatusCode();
            $body = $response->getContent(false);

            if ($status < 200 || $status >= 300) {
                $this->logger?->error('ReportService Grok', [
                    'model' => $model,
                    'status' => $status,
                    'body' => $body,
                ]);
                return null;
            }

            $data = json_decode($body, true);
            if (!\is_array($data)) {
                return null;
            }
            $content = $data['choices'][0]['message']['content'] ?? $data['output'] ?? $data['message']['content'] ?? null;
            if ($content !== null && $content !== '') {
                return trim((string) $content);
            }
            return null;
        } catch (\Throwable $e) {
            $detail = $e->getMessage();
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                try {
                    $detail .= ' | ' . $e->getResponse()->getContent(false);
                } catch (\Throwable $t) {
                }
            }
            $this->logger?->error('ReportService Grok (' . $model . '): ' . $detail);

            return null;
        }
    }

    /**
     * Synthèse de secours (sans API) lorsque Grok est indisponible.
     *
     * @param array{events_count: int, categories_count: int, participations_count: int, ratings_count: int, certificats_count: int, events_by_category: array<string, int>} $stats
     */
    private function buildFallbackSummary(array $stats): string
    {
        $e = $stats['events_count'];
        $c = $stats['categories_count'];
        $p = $stats['participations_count'];
        $r = $stats['ratings_count'];
        $cert = $stats['certificats_count'];

        $parts = [];
        $parts[] = sprintf(
            'Ce rapport recense %d événement(s), %d catégorie(s), %d participation(s), %d avis et %d certificat(s).',
            $e,
            $c,
            $p,
            $r,
            $cert,
        );
        if ($e > 0 && $c > 0 && $stats['events_by_category'] !== []) {
            $top = [];
            foreach ($stats['events_by_category'] as $name => $count) {
                if ($name !== '' && $count > 0) {
                    $top[] = $name . ' (' . $count . ')';
                }
            }
            if ($top !== []) {
                $parts[] = 'Répartition par catégorie : ' . implode(', ', $top) . '.';
            }
        }
        if ($p > 0) {
            $parts[] = 'L\'engagement des participants et les avis recueillis constituent des indicateurs utiles pour la suite.';
        }

        return implode(' ', $parts);
    }
}
