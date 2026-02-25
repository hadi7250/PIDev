<?php

namespace App\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CodeCompiler
{
    private const SUPPORTED_LANGUAGES = [
        'php' => ['ext' => 'php', 'command' => ['php']],
        'python' => ['ext' => 'py', 'command' => ['python3']],
        'javascript' => ['ext' => 'js', 'command' => ['node']],
        'java' => ['ext' => 'java', 'command' => ['java']],
        'cpp' => ['ext' => 'cpp', 'command' => ['g++', '-o', '/tmp/program', '{file}', '&&', '/tmp/program']],
        'csharp' => ['ext' => 'cs', 'command' => ['csc']],
        'go' => ['ext' => 'go', 'command' => ['go', 'run']],
        'rust' => ['ext' => 'rs', 'command' => ['rustc', '-o', '/tmp/program', '{file}', '&&', '/tmp/program']],
    ];

    private const TIMEOUT = 10;
    private const MAX_OUTPUT_LENGTH = 5000;

    /**
     * Compile and execute code
     */
    public function compile(string $code, string $language): array
    {
        if (!isset(self::SUPPORTED_LANGUAGES[$language])) {
            return [
                'success' => false,
                'output' => '',
                'error' => "Language '$language' is not supported.",
            ];
        }

        try {
            $langConfig = self::SUPPORTED_LANGUAGES[$language];
            $ext = $langConfig['ext'];
            
            // Create temporary file
            $tmpFile = tempnam(sys_get_temp_dir(), 'code_');
            $tmpFile .= '.' . $ext;
            file_put_contents($tmpFile, $code);

            // Build command
            $command = array_map(fn($part) => str_replace('{file}', $tmpFile, $part), $langConfig['command']);
            
            // Execute
            $process = new Process($command);
            $process->setTimeout(self::TIMEOUT);
            $process->run();

            // Clean up
            @unlink($tmpFile);
            if (str_contains($langConfig['command'][0], 'g++') || str_contains($langConfig['command'][0], 'rustc')) {
                @unlink('/tmp/program');
            }

            $output = $process->getOutput();
            $error = $process->getErrorOutput();

            // Truncate output if too long
            if (strlen($output) > self::MAX_OUTPUT_LENGTH) {
                $output = substr($output, 0, self::MAX_OUTPUT_LENGTH) . "\n[Output truncated...]";
            }

            return [
                'success' => $process->isSuccessful(),
                'output' => $output,
                'error' => $error ?: null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'output' => '',
                'error' => 'Compilation error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract blanks from template and generate fill-in-the-blanks exercise
     * Blanks are marked with [BLANK] or [BLANK:hint]
     */
    public function extractBlanks(string $templateCode): array
    {
        $blanks = [];
        $pattern = '/\[BLANK(?::(.+?))?\]/';
        
        preg_match_all($pattern, $templateCode, $matches, PREG_OFFSET_CAPTURE);
        
        foreach ($matches[0] as $index => $match) {
            $blanks[] = [
                'index' => $index + 1,
                'placeholder' => $match[0],
                'hint' => $matches[1][$index][0] ?? null,
                'position' => $match[1],
            ];
        }

        $displayCode = preg_replace($pattern, '___________', $templateCode);

        return [
            'blanks' => $blanks,
            'displayCode' => $displayCode,
            'totalBlanks' => count($blanks),
        ];
    }

    /**
     * Validate filled-in code against solution
     */
    public function validateSolution(string $filledCode, string $solutionCode, string $language): array
    {
        // Simple string comparison (can be enhanced with semantic analysis)
        $normalizedFilled = preg_replace('/\s+/', '', $filledCode);
        $normalizedSolution = preg_replace('/\s+/', '', $solutionCode);

        if ($normalizedFilled === $normalizedSolution) {
            return [
                'isCorrect' => true,
                'message' => 'Excellent! Your solution is correct!',
            ];
        }

        // Try to compile and run to give partial credit
        $result = $this->compile($filledCode, $language);
        
        return [
            'isCorrect' => false,
            'message' => $result['success'] 
                ? 'Code compiles but the solution differs from expected.'
                : 'Code compilation failed.',
            'compilationResult' => $result,
        ];
    }

    /**
     * Get list of supported languages
     */
    public function getSupportedLanguages(): array
    {
        return array_keys(self::SUPPORTED_LANGUAGES);
    }
}
