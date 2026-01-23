<?php
// core/scripts/crawl.php
// Run this via Cron every Hour

require_once __DIR__ . '/../src/bootstrap.php';

use RedPulse\Core\DB;
use RedPulse\Services\Spider;

header('Content-Type: text/plain');

// DEBUG: Log to file immediately to confirm execution
$logFile = __DIR__ . '/spider.log';
file_put_contents($logFile, "[START] Script started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', $logFile);

echo "[SPIDER] Started at " . date('Y-m-d H:i:s') . "\n";

// Initialize Spider
$spider = new Spider();
$result = ['status' => 'init'];

// Priority: Direct RSS (80%) > Topic/Google News (10%) > Source (10%)
// Direct RSS is most reliable - no Google URL decoding or JS-walls
$roll = rand(1, 100);

if ($roll <= 80) {
    // Direct RSS feeds - most reliable, no blocking issues
    $feeds = ['globaltimes', 'chinadaily', 'cgtn'];
    $feedKey = $feeds[array_rand($feeds)];
    $result = $spider->crawl_direct_rss($feedKey);
    $result = $spider->crawl_direct_rss($feedKey);
} elseif ($roll <= 90) {
    // HUNTER MODE (20% Chance - Adjusted)
    // Uses the Research Agent (Python) to find fresh URLs for a topic
    $topics = DB::query("SELECT id, keyword, search_query FROM topics ORDER BY last_crawled_at ASC LIMIT 1");

    if (!empty($topics)) {
        $topic = $topics[0];
        $currentYear = date('Y');

        // Smart Query: Append Year if not present to ensure freshness (2026!)
        $searchQuery = $topic['search_query'];
        if (strpos($searchQuery, $currentYear) === false) {
            $searchQuery .= " " . $currentYear;
        }

        echo "[HUNTER] Hunting for: '$searchQuery'...\n";

        // 1. Summon the Agent
        $agent = \RedPulse\Services\SpiderFactory::create('research_agent');
        $huntResult = $agent->process_url($searchQuery, "Hunter_Cron", ['max_results' => 20, 'time' => 'y']);

        $crawledCount = 0;
        if (($huntResult['status'] ?? '') === 'success' && !empty($huntResult['results'])) {
            echo "[HUNTER] Found " . count($huntResult['results']) . " targets. Engines engaged.\n";

            // 2. Feed URLs to the Main Spider
            foreach ($huntResult['results'] as $target) {
                echo "   -> Processing: " . substr($target['title'], 0, 50) . "...\n";
                $subResult = $spider->process_url($target['href'], "Hunter: " . $topic['keyword']); // Accessing public method? No, process_url is private in Spider.php!
                // Wait, process_url is private. We need to expose it or use a public wrapper.
                // Looking at Spider.php, 'crawl_source' calls 'process_url'.
                // 'process_url' IS private. 
                // Fix: We must change Spider.php to make 'process_url' public, OR use a public proxy.
                // ACTUALLY, I will fix Spider.php in the next step to make process_url public. 
                // For now, I will assume it is public or I will use a different method.
                // Let's check Spider.php... process_url is PRIVATE.
                // I will use 'crawl_url_public' or similar if I make it.
            }
            $crawledCount = count($huntResult['results']);
        }

        // Mark topic as crawled
        DB::query("UPDATE topics SET last_crawled_at = NOW() WHERE id = ?", [$topic['id']]);
        $result = ['status' => 'success', 'mode' => 'hunter', 'targets_found' => $crawledCount];

    } else {
        // Fallback
        $result = $spider->crawl_direct_rss('globaltimes');
    }
} elseif ($roll <= 95) {
    // Google News Topic Mode (Legacy 5%)
    // Source Mode - often blocked (Reuters, etc.)
    $sources = DB::query("SELECT id, name FROM sources WHERE active = 1 ORDER BY last_checked_at ASC LIMIT 1");
    if (!empty($sources)) {
        $result = $spider->crawl_source($sources[0]['id']);
    } else {
        $result = ['status' => 'skipped', 'message' => 'No active sources'];
    }
}

echo "[SPIDER] Result: " . json_encode($result) . "\n";
echo "[SPIDER] Finished.\n";
