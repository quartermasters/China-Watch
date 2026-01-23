<?php
// Simple Cron Test Script
$result = [
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'path' => __DIR__,
    'file' => __FILE__,
    'user' => get_current_user(),
];

// Write to a log file in the same directory
$logFile = __DIR__ . '/test_cron.log';
file_put_contents($logFile, print_r($result, true));

echo "[SUCCESS] Test finished. Log saved to: $logFile";
