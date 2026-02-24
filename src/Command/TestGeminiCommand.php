<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:test-gemini')]
class TestGeminiCommand extends Command
{
    public function __construct(private HttpClientInterface $httpClient) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

        $io->info('Testing connection with key: ' . substr($apiKey, 0, 8) . '...');

        try {
            // UPDATED: Pointing to the active gemini-2.5-flash model
            $response = $this->httpClient->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => 'Réponds exactement par le mot SUCCESS si tu m\'entends.']]]
                    ]
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $data = $response->toArray();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No text in response';
                $io->success('IT WORKS! Gemini says: ' . $reply);
                return Command::SUCCESS;
            }

            $io->error('API Error: Status ' . $statusCode);
            return Command::FAILURE;

        } catch (\Exception $e) {
            $io->error('Connection failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}