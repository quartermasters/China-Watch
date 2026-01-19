<?php
// reset_monitors.php
require_once __DIR__ . '/../core/src/bootstrap.php';
use RedPulse\Core\DB;

echo "<h1>System Reset</h1>";
DB::query("UPDATE sources SET last_checked_at = NULL");
echo "<p style='color:green'>[OK] All Monitors have been reset. Run Cron now.</p>";
