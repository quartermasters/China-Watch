<?php
// core/scripts/test_perplexity.php

// 1. Get API Key from environment or hardcode for testing
$apiKey = getenv('PERPLEXITY_API_KEY') ?: 'YOUR_KEY_HERE';

if ($apiKey === 'YOUR_KEY_HERE') {
    die("❌ Please set your PERPLEXITY_API_KEY in this file or environment.\n");
}

$url = 'https://api.perplexity.ai/chat/completions';

$data = [
    'model' => 'sonar-pro', // The "Reasoning" model with web access
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are a precise OSINT analyst. Provide a summary of the latest news regarding this topic and CITE your sources.'
        ],
        [
            'role' => 'user',
            'content' => 'What are the most recent regulations on China exporting logic chips?'
        ]
    ],
    'max_tokens' => 1000,
    'temperature' => 0.2,
    'return_citations' => true // CRITICAL: This gives us the URLs!
];

echo "🚀 Sending request to Perplexity (sonar-pro)...\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($info['http_code'] === 200) {
    $json = json_decode($response, true);
    $content = $json['choices'][0]['message']['content'];
    $citations = $json['citations'] ?? [];

    echo "\n✅ **REPORT:**\n";
    echo $content . "\n\n";

    echo "🔗 **SOURCES (Citations):**\n";
    foreach ($citations as $i => $cite) {
        echo "[" . ($i + 1) . "] $cite\n";
    }
} else {
    echo "❌ Error: " . $response;
}
