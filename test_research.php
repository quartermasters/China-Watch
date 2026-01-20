<?php
// test_research.php
require_once __DIR__ . '/core/src/bootstrap.php';

use RedPulse\Services\SpiderFactory;

echo "🕵️  Testing Research Agent (DuckDuckGo)...\n";

// 1. Create the specialized spider
// The factory will look for 'scrape_research_agent.py' because of the key
$agent = SpiderFactory::create('research_agent');

// 2. Execute a search
$query = "China Economy Outlook 2025";
echo "🔎 Searching for: '$query'...\n";

// The Wrapper passes the query as the 'url' argument
$result = $agent->process_url($query, "Manual Test");

// 3. Output results
echo "\n--- RAW JSON RESULT ---\n";
print_r($result);

if (($result['status'] ?? '') === 'success') {
    echo "\n✅ SUCCESS: Found " . count($result['results'] ?? []) . " results.\n";
    foreach (($result['results'] ?? []) as $idx => $item) {
        if ($idx >= 3)
            break; // Show top 3
        echo "   - [{$item['title']}]({$item['href']})\n";
    }
} else {
    echo "\n❌ FAILURE: " . ($result['message'] ?? 'Unknown error') . "\n";
}
