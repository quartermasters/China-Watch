<?php
require_once __DIR__ . '/core/src/Core/DB.php';
require_once __DIR__ . '/core/src/Core/Config.php';

use RedPulse\Core\DB;

// 1. Check if DB connection works
try {
    $rows = DB::query("SELECT count(*) as count FROM reports");
    echo "Total Reports: " . $rows[0]['count'] . "\n";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
    exit;
}

// 2. Check Raw Tags
$rows = DB::query("SELECT id, tags FROM reports LIMIT 5");
echo "\n--- Raw Tags Sample ---\n";
foreach ($rows as $r) {
    echo "ID " . $r['id'] . ": " . ($r['tags'] ? $r['tags'] : "[NULL]") . "\n";
    $decoded = json_decode($r['tags'], true);
    echo "Decoded: " . (json_last_error() === JSON_ERROR_NONE ? print_r($decoded, true) : "JSON ERROR") . "\n";
}

// 3. Check Aggregation Logic
$allTags = DB::query("SELECT tags FROM reports WHERE tags IS NOT NULL AND tags != ''");
$count = 0;
$tagMap = [];
foreach ($allTags as $r) {
    $decoded = json_decode($r['tags'], true);
    if (is_array($decoded)) {
        foreach ($decoded as $t) {
            $tagMap[$t] = ($tagMap[$t] ?? 0) + 1;
            $count++;
        }
    }
}
echo "\nTotal Tag Occurrences Found: $count\n";
echo "Unique Tags: " . count($tagMap) . "\n";
print_r(array_slice($tagMap, 0, 5));
