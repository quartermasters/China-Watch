<?php
// core/scripts/crawl.php
// Run this via Cron every Hour

require_once __DIR__ . '/../src/bootstrap.php';

use RedPulse\Core\DB;
use RedPulse\Services\Spider;

header('Content-Type: text/plain');
echo "[SPIDER] Started at " . date('Y-m-d H:i:s') . "\n";

// Get a target source (oldest checked first)
$sources = DB::query("SELECT id, name FROM sources WHERE active = 1 ORDER BY last_checked_at ASC LIMIT 1");

if (empty($sources)) {
    echo "[SPIDER] No active sources found.\n";
    exit;
}

$source = $sources[0];
echo "[SPIDER] Crawling {$source['name']}...\n";

$spider = new Spider();
$result = $spider->crawl_source($source['id']);

echo "[SPIDER] Result: " . json_encode($result) . "\n";
echo "[SPIDER] Finished.\n";
