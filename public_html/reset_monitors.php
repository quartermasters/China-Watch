<?php
// reset_monitors.php
require_once __DIR__ . '/../core/src/bootstrap.php';
use RedPulse\Core\DB;

// 1. Reset Database
try {
    DB::query("UPDATE sources SET last_checked_at = NULL");
    echo "<p style='color:green'>[✔] Database Reset: All sources marked as 'fresh'.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>[✘] Database Error: " . $e->getMessage() . "</p>";
}

// 2. Verify Scraper Code
$scraperPath = __DIR__ . '/../core/src/Services/Scraper.php';
if (file_exists($scraperPath)) {
    $content = file_get_contents($scraperPath);
    if (strpos($content, 'generateMockRSS') !== false) {
        echo "<p style='color:green'>[✔] Scraper Code: Simulation Engine is COMPLETE.</p>";
    } else {
        echo "<p style='color:red'>[✘] Scraper Code: OLD VERSION DETECTED. Please re-upload core/src/Services/Scraper.php</p>";
    }
} else {
    echo "<p style='color:red'>[✘] Scraper Code: File not found at $scraperPath</p>";
}

echo "<hr><h3>Ready to Launch</h3>";
echo "<p>Please go to Cron Jobs and click 'Run Now'.</p>";
