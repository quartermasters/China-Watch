<?php
// core/scripts/crawl.php
// Run this via Cron every Hour

require_once __DIR__ . '/../src/bootstrap.php';

use RedPulse\Core\DB;
use RedPulse\Services\Spider;

header('Content-Type: text/plain');
echo "[SPIDER] Started at " . date('Y-m-d H:i:s') . "\n";

// 50/50 Chance to crawl a specific Source OR a Topic discovery
$mode = (rand(0, 1) === 0) ? 'source' : 'topic';

if ($mode === 'source') {
    $sources = DB::query("SELECT id, name FROM sources WHERE active = 1 ORDER BY last_checked_at ASC LIMIT 1");
    if (!empty($sources)) {
        $spider->crawl_source($sources[0]['id']);
    }
} else {
    // Topic Mode
    $topics = DB::query("SELECT id, keyword FROM topics ORDER BY last_crawled_at ASC LIMIT 1");
    if (!empty($topics)) {
        $spider->crawl_topic($topics[0]['id']);
    } else {
        // Fallback if no topics table yet
        $spider->crawl_source(DB::query("SELECT id FROM sources LIMIT 1")[0]['id']);
    }
}

echo "[SPIDER] Result: " . json_encode($result) . "\n";
echo "[SPIDER] Finished.\n";
