<?php
declare(strict_types=1);

namespace RedPulse\Services;

class PerplexityService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.perplexity.ai/chat/completions';
    private string $model = 'sonar-pro';

    public function __construct()
    {
        if (!defined('PERPLEXITY_API_KEY') || empty(PERPLEXITY_API_KEY)) {
            throw new \Exception("PERPLEXITY_API_KEY is not defined in env.php");
        }
        $this->apiKey = PERPLEXITY_API_KEY;
    }

    /**
     * Conducts deep research on a topic using Perplexity's online model.
     * Returns an array containing the written report and a list of citation URLs.
     */
    public function research_topic(string $topic): array
    {
        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a senior OSINT analyst for "China Watch". functionality:
                    1. Search for the latest, high-impact developments regarding the user provided topic.
                    2. Write a concise intelligence summary (3-4 paragraphs).
                    3. CITE your sources heavily.
                    4. Focus on reputable sources (Reuters, SCMP, Caixin, government notices).
                    5. IGNORE generic wikis or dated content provided > 1 month ago unless contextually vital.'
                ],
                [
                    'role' => 'user',
                    'content' => $topic
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.2, // Low temperature for factual accuracy
            'return_citations' => true
        ];

        $response = $this->make_request($payload);

        if (empty($response['choices'][0]['message']['content'])) {
            return ['status' => 'error', 'message' => 'Empty response from Perplexity'];
        }

        return [
            'status' => 'success',
            'report_content' => $response['choices'][0]['message']['content'],
            'citations' => $response['citations'] ?? [],
            'model_used' => $this->model
        ];
    }

    private function make_request(array $payload): array
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->apiKey}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Give it time to search the web

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("Perplexity API Error ($httpCode): " . substr($result, 0, 200));
        }

        $json = json_decode($result, true);
        if (!$json) {
            throw new \Exception("Invalid JSON response from Perplexity");
        }

        return $json;
    }
}
