<?php
declare(strict_types=1);

namespace RedPulse\Services\AI;

use RedPulse\Core\DB;

class ReportGenerator
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = OPENAI_API_KEY;
    }

    public function generate_report(string $sourceName, string $sourceUrl, string $rawText): int
    {
        // 1. Prepare Prompt
        // Truncate text to fit context window (approx 10k chars for safety if using mini, max for 5.2)
        $context = substr($rawText, 0, 15000);

        $prompt = "
        You are the Editor-in-Chief of 'China Watch'. 
        Analyze the following raw text crawled from source '{$sourceName}' ({$sourceUrl}).
        
        Write a professional, high-level intelligence report based on this content.
        
        FORMAT (HTML):
        <p>[Intro]</p>
        <h2>Strategic Analysis</h2>
        <p>[Analysis]</p>
        <h2>Key Risks</h2>
        <ul><li>[Risk 1]</li>...</ul>
        <h2>Conclusion</h2>
        <p>[Conclusion]</p>

        Also provide a JSON object at the very end separated by '||JSON||' with metadata:
        { \"title\": \"Catchy Headline\", \"summary\": \"2-sentence summary\", \"tags\": [\"Tag1\", \"Tag2\"] }

        RAW CONTENT:
        {$context}
        ";

        // 2. Call OpenAI (GPT-5.2)
        $response = $this->call_gpt($prompt);

        // 3. Parse Response
        $parts = explode('||JSON||', $response);
        $htmlContent = trim($parts[0]);
        $metadata = json_decode(trim($parts[1] ?? '{}'), true);

        $title = $metadata['title'] ?? "Intelligence Report: {$sourceName}";
        $summary = $metadata['summary'] ?? "Automated analysis of data from {$sourceName}.";
        $tags = json_encode($metadata['tags'] ?? ['Unclassified']);
        $slug = 'report-' . uniqid() . '-' . date('Y-m-d');

        // 4. Save to Database
        DB::query(
            "INSERT INTO reports (slug, title, summary, content, tags, source_url, published_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$slug, $title, $summary, $htmlContent, $tags, $sourceUrl]
        );

        $id = DB::query("SELECT id FROM reports WHERE slug = ?", [$slug])[0]['id'];
        return (int) $id;
    }

    private function call_gpt(string $prompt): string
    {
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => 'gpt-5.2',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an advanced economic intelligence engine.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result, true);
        return $json['choices'][0]['message']['content'] ?? "Error generating report.";
    }
}
