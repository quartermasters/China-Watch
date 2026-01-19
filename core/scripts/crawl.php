<?php
// core/scripts/crawl.php
// Run this via Cron every Hour

require_once __DIR__ . '/../src/bootstrap.php';

use RedPulse\Core\DB;
use RedPulse\Services\Spider;

header('Content-Type: text/plain');
echo "[SPIDER] Started at " . date('Y-m-d H:i:s') . "\n";

// Initialize Spider
$spider = new Spider();
$result = ['status' => 'init'];

// Priority: Direct RSS (70%) > Topic/Google News (20%) > Source (10%)
// Direct RSS is most reliable - no Google URL decoding or JS-walls
$roll = rand(1, 100);

if ($roll <= 70) {
    // Direct RSS feeds - most reliable, no blocking issues
    $feeds = ['globaltimes', 'chinadaily'];
    $feedKey = $feeds[array_rand($feeds)];
    $result = $spider->crawl_direct_rss($feedKey);
} elseif ($roll <= 90) {
    // Topic Mode (Google News) - often blocked by JS-walls
    $topics = DB::query("SELECT id, keyword FROM topics ORDER BY last_crawled_at ASC LIMIT 1");
    if (!empty($topics)) {
        $result = $spider->crawl_topic($topics[0]['id']);
    } else {
        $result = $spider->crawl_direct_rss('globaltimes');
    }
} else {
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
