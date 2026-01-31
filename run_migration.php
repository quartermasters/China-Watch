<?php
// Run Migration Script
require_once __DIR__ . '/core/src/bootstrap.php';
use RedPulse\Core\DB;

echo "Running Migration 006 (Users)...\n";

try {
    $sql = file_get_contents(__DIR__ . '/core/sql/migration_006_users.sql');
    if (!$sql) {
        die("Error: Could not read migration file.\n");
    }

    // Split by statement if needed, or run as block if driver supports it. 
    // PDO::exec works for multiple statements if emulation is on or supported.
    DB::query($sql);
    echo "Success: Migration completed.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
