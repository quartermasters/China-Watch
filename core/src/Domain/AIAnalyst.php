<?php
declare(strict_types=1);

namespace RedPulse\Domain;

class AIAnalyst
{

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = OPENAI_API_KEY;
    }

    public function ask(string $userQuestion, array $dataContext): string
    {
        $url = 'https://api.openai.com/v1/chat/completions';

        // Build System Prompt with current data context
        $systemPrompt = "You are 'China Watch', an advanced China economic analyst. \n";
        $systemPrompt .= "You answer strictly based on the provided Data Context. Be concise, professional, and military-grade in tone. \n\n";
        $systemPrompt .= "DATA CONTEXT: \n";
        foreach ($dataContext as $key => $val) {
            $systemPrompt .= "- {$key}: {$val}\n";
        }

        $data = [
            'model' => 'gpt-4o-mini', // Cost-effective
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userQuestion]
            ],
            'temperature' => 0.1, // High precision
            'max_tokens' => 150
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        // Use cURL for robustness
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return "Connection secure. Signal lost. (API Error: {$httpCode})";
        }

        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? "Data stream corrupted.";
    }
}
