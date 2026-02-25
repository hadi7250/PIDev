<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CodeQuestionGenerator
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $provider; // 'gemini' or 'grok'

    public function __construct(string $geminiApiKey = null, string $grokApiKey = null)
    {
        $this->httpClient = HttpClient::create();
        
        // Default to Gemini if available, fallback to Grok
        if (!empty($geminiApiKey)) {
            $this->apiKey = $geminiApiKey;
            $this->provider = 'gemini';
        } elseif (!empty($grokApiKey)) {
            $this->apiKey = $grokApiKey;
            $this->provider = 'grok';
        } else {
            // For demo purposes, use a placeholder
            $this->apiKey = 'demo-key';
            $this->provider = 'gemini';
        }
    }

    public function generateQuestion(string $prompt, string $language = 'PHP'): array
    {
        // For demo/testing without API keys, generate a sample question
        if ($this->apiKey === 'demo-key') {
            return $this->generateDemoQuestion($prompt, $language);
        }
        
        $generatedText = $this->callAiAPI($prompt, $language);
        return $this->parseGeneratedQuestion($generatedText, $language);
    }

    private function generateDemoQuestion(string $prompt, string $language): array
    {
        $demoQuestions = [
            'PHP' => [
                'title' => 'Implement Array Filtering Function',
                'template' => '<?php
function filterArray($array, $callback) {
    $result = [];
    foreach ($array as $item) {
        if ([BLANK]) {
            $result[] = $item;
        }
    }
    return $result;
}

$numbers = [1, 2, 3, 4, 5];
$evenNumbers = filterArray($numbers, function($n) { return $n % 2 == 0; });
print_r([BLANK]);',
                'solution' => '<?php
function filterArray($array, $callback) {
    $result = [];
    foreach ($array as $item) {
        if ($callback($item)) {
            $result[] = $item;
        }
    }
    return $result;
}

$numbers = [1, 2, 3, 4, 5];
$evenNumbers = filterArray($numbers, function($n) { return $n % 2 == 0; });
print_r($evenNumbers);',
                'difficulty' => 3,
                'hints' => ['Use the callback function with the item', 'Print the result array'],
                'description' => 'Create a function that filters an array using a callback function. The function should iterate through the array and keep only items where the callback returns true.'
            ]
        ];

        $demoQuestions['Python'] = [
            'title' => 'Implement List Comprehension',
            'template' => 'def square_filter(numbers):
    """Return list of squared even numbers"""
    return [BLANK]

numbers = [1, 2, 3, 4, 5, 6]
result = square_filter(numbers)
print([BLANK])',
            'solution' => 'def square_filter(numbers):
    """Return list of squared even numbers"""
    return [x**2 for x in numbers if x % 2 == 0]

numbers = [1, 2, 3, 4, 5, 6]
result = square_filter(numbers)
print(result)',
            'difficulty' => 2,
            'hints' => ['Use list comprehension with a condition', 'Filter for even numbers and square them'],
            'description' => 'Create a function using list comprehension that returns squared values of even numbers from the input list.'
        ];

        $demoQuestions['JavaScript'] = [
            'title' => 'Arrow Function and Array Methods',
            'template' => 'const numbers = [1, 2, 3, 4, 5];
const result = numbers
    .filter([BLANK])
    .map([BLANK]);

console.log([BLANK]);',
            'solution' => 'const numbers = [1, 2, 3, 4, 5];
const result = numbers
    .filter(n => n % 2 === 0)
    .map(n => n * n);

console.log(result);',
            'difficulty' => 2,
            'hints' => ['Filter for even numbers using modulo', 'Map to square each number', 'Log the final result'],
            'description' => 'Use array methods to filter even numbers and square them using arrow functions.'
        ];

        $demoQuestions['Java'] = [
            'title' => 'Java Stream API Filter and Map',
            'template' => 'List<Integer> numbers = Arrays.asList(1, 2, 3, 4, 5);
List<Integer> result = numbers.stream()
    .filter([BLANK])
    .map([BLANK])
    .[BLANK];
System.out.println(result);',
            'solution' => 'List<Integer> numbers = Arrays.asList(1, 2, 3, 4, 5);
List<Integer> result = numbers.stream()
    .filter(n -> n % 2 == 0)
    .map(n -> n * n)
    .collect(Collectors.toList());
System.out.println(result);',
            'difficulty' => 3,
            'hints' => ['Filter even numbers', 'Map to square each number', 'Collect to list'],
            'description' => 'Use Java Stream API to filter even numbers and map them to their squares.'
        ];

        $language_key = in_array($language, ['PHP', 'Python', 'JavaScript', 'Java']) ? $language : 'PHP';
        $question = $demoQuestions[$language_key] ?? $demoQuestions['PHP'];
        
        return [
            'title' => $question['title'],
            'template' => $question['template'],
            'solution' => $question['solution'],
            'difficulty' => $question['difficulty'],
            'hints' => $question['hints'],
            'description' => $question['description'],
            'language' => $language
        ];
    }

    private function callAiAPI(string $prompt, string $language): string
    {
        $systemPrompt = "You are an expert programming tutor. Generate a code exercise in $language language based on the user's request. 
        Provide the response in this exact JSON format:
        {
            \"title\": \"Exercise title\",
            \"template\": \"Code template with [BLANK] placeholders where student should fill in code\",
            \"solution\": \"Complete working solution\",
            \"difficulty\": 1-5 (where 1 is beginner, 5 is advanced),
            \"hints\": [\"hint1\", \"hint2\", \"hint3\"],
            \"description\": \"Detailed explanation of the exercise\"
        }
        Make the template have 2-4 [BLANK] placeholders.";

        if ($this->provider === 'gemini') {
            return $this->callGeminiAPI($systemPrompt, $prompt);
        } else {
            return $this->callGrokAPI($systemPrompt, $prompt);
        }
    }

    private function callGeminiAPI(string $systemPrompt, string $userPrompt): string
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
        
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $systemPrompt . "\n\nUser request: " . $userPrompt
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->httpClient->request('POST', $url, [
                'json' => $payload,
                'query' => ['key' => $this->apiKey],
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = $response->toArray();
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
            
            throw new \Exception('Unexpected Gemini API response format');
        } catch (\Exception $e) {
            throw new \Exception('Gemini API call failed: ' . $e->getMessage());
        }
    }

    private function callGrokAPI(string $systemPrompt, string $userPrompt): string
    {
        $url = 'https://api.x.ai/v1/chat/completions';
        
        $payload = [
            'model' => 'grok-2-1212',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000
        ];

        try {
            $response = $this->httpClient->request('POST', $url, [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = $response->toArray();
            
            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }
            
            throw new \Exception('Unexpected Grok API response format');
        } catch (\Exception $e) {
            throw new \Exception('Grok API call failed: ' . $e->getMessage());
        }
    }

    private function parseGeneratedQuestion(string $generatedText, string $language): array
    {
        // Extract JSON from response (handle cases where API wraps JSON in markdown or text)
        $jsonMatch = null;
        if (preg_match('/```json\s*(.*?)\s*```/s', $generatedText, $matches)) {
            $jsonMatch = $matches[1];
        } elseif (preg_match('/\{[\s\S]*\}/', $generatedText, $matches)) {
            $jsonMatch = $matches[0];
        }

        if (!$jsonMatch) {
            throw new \Exception('Could not extract JSON from AI response');
        }

        $parsed = json_decode($jsonMatch, true);
        
        if (!$parsed) {
            throw new \Exception('Invalid JSON in AI response: ' . json_last_error_msg());
        }

        // Ensure all required fields exist
        return [
            'title' => $parsed['title'] ?? 'Generated Exercise',
            'template' => $parsed['template'] ?? '',
            'solution' => $parsed['solution'] ?? '',
            'difficulty' => min(5, max(1, $parsed['difficulty'] ?? 3)),
            'hints' => is_array($parsed['hints'] ?? []) ? $parsed['hints'] : [],
            'description' => $parsed['description'] ?? '',
            'language' => $language
        ];
    }
}
