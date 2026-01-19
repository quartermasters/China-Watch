<?php
// core/scripts/heartbeat.php
// Run this via Cron every Minute: php /path/to/core/scripts/heartbeat.php

// 1. Bootstrap
require_once __DIR__ . '/../src/bootstrap.php';

use RedPulse\Core\DB;
use RedPulse\Services\Scraper;

// Set Text Response for Cron Logs
header('Content-Type: text/plain');
echo "[HEARTBEAT] Started at " . date('Y-m-d H:i:s') . "\n";

try {
    // 2. Find Stale Source (Round Robin)
    // "Find active sources where:
    // (Checked > Frequency minutes ago) OR (Never Checked)"
    // Order by oldest check first. Limit 1 to prevent timeout.

    $sql = "SELECT * FROM sources 
            WHERE active = 1 
            AND (last_checked_at IS NULL OR last_checked_at < DATE_SUB(NOW(), INTERVAL frequency_minutes MINUTE))
            ORDER BY last_checked_at ASC 
            LIMIT 1";

    $queue = DB::query($sql);

    if (empty($queue)) {
        echo "[HEARTBEAT] All sources are fresh. Nothing to do.\n";
        exit;
    }

    $source = $queue[0];
    echo "[HEARTBEAT] Processing Source: {$source['name']} ({$source['osint_id']})...\n";

    // 3. Execute Scraper
    $scraper = new Scraper();
    $result = $scraper->processSource($source['osint_id']);

    // 4. Report
    if ($result['status'] === 'success') {
        echo "[SUCCESS] Processed. Data: " . json_encode($result['data']) . "\n";
    } else {
        echo "[ERROR] Failed: " . $result['message'] . "\n";
    }

} catch (Throwable $e) {
    echo "[CRITICAL] Exception: " . $e->getMessage() . "\n";
}

echo "[HEARTBEAT] Finished at " . date('Y-m-d H:i:s') . "\n";
