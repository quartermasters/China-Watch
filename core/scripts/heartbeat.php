<?php
// core/scripts/heartbeat.php
// ALIAS FOR CRAWL.PHP
// This ensures that legacy cron jobs running "heartbeat.php" still execute the modern "crawl.php" logic.

require_once __DIR__ . '/crawl.php';
