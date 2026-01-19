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

// FORCE TOPIC MODE (Temporary Fix to verify GZIP Logic)
// Reuters is blocking us, so let's focus on the Google News Topics which are working but just need GZIP.
$mode = 'topic';

if ($mode === 'source') {
    $sources = DB::query("SELECT id, name FROM sources WHERE active = 1 ORDER BY last_checked_at ASC LIMIT 1");
    if (!empty($sources)) {
        $result = $spider->crawl_source($sources[0]['id']);
    } else {
        $result = ['status' => 'skipped', 'message' => 'No active sources'];
    }
} else {
    // Topic Mode
    $topics = DB::query("SELECT id, keyword FROM topics ORDER BY last_crawled_at ASC LIMIT 1");
    if (!empty($topics)) {
        $result = $spider->crawl_topic($topics[0]['id']);
    } else {
        $result = ['status' => 'skipped', 'message' => 'No topics found'];
    }
}

echo "[SPIDER] Result: " . json_encode($result) . "\n";
echo "[SPIDER] Finished.\n";
