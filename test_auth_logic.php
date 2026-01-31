<?php
// Test Auth Logic
require_once __DIR__ . '/core/src/bootstrap.php';
use RedPulse\Core\DB;

echo "Testing User Upsert Logic...\n";

// Mock Data
$mockUser = [
    'google_id' => 'test_gid_' . time(),
    'email' => 'test_user_' . time() . '@example.com',
    'name' => 'Test User',
    'avatar' => 'https://example.com/avatar.png'
];

try {
    // 1. Test Insert
    echo "1. Testing Insert... ";

    // Manually replicate AuthService logic since methods are private/protected or dependent on Client
    // We are testing the DB interaction part mainly.
    DB::query(
        "INSERT INTO users (google_id, email, name, avatar, last_login) VALUES (?, ?, ?, ?, NOW())",
        [$mockUser['google_id'], $mockUser['email'], $mockUser['name'], $mockUser['avatar']]
    );

    // Verify
    $check = DB::query("SELECT * FROM users WHERE google_id = ?", [$mockUser['google_id']]);
    if (count($check) === 1 && $check[0]['email'] === $mockUser['email']) {
        echo "PASS (ID: " . $check[0]['id'] . ")\n";
    } else {
        echo "FAIL\n";
        exit(1);
    }

    // 2. Test Update
    echo "2. Testing Update... ";
    $newAvatar = 'https://example.com/new_avatar.png';
    DB::query(
        "UPDATE users SET avatar = ?, last_login = NOW() WHERE google_id = ?",
        [$newAvatar, $mockUser['google_id']]
    );

    // Verify
    $checkUpdate = DB::query("SELECT * FROM users WHERE google_id = ?", [$mockUser['google_id']]);
    if ($checkUpdate[0]['avatar'] === $newAvatar) {
        echo "PASS\n";
    } else {
        echo "FAIL\n";
    }

    // Cleanup
    DB::query("DELETE FROM users WHERE google_id = ?", [$mockUser['google_id']]);
    echo "Cleanup complete.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
