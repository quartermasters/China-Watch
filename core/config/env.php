<?php
declare(strict_types=1);

// Database Credentials (Hostinger)
// UPDATE THESE WITH YOUR ACTUAL HOSTINGER DATABASE DETAILS
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u123456789_redpulse'); // Example Hostinger DB Name
define('DB_USER', 'u123456789_admin');    // Example Hostinger User
define('DB_PASS', 'YourStrongPassword123!');

// API Keys (Injected)
define('GOOGLE_MAPS_KEY', 'YOUR_GOOGLE_MAPS_KEY');
define('OPENAI_API_KEY', 'YOUR_OPENAI_API_KEY');

// System Configuration
define('SITE_NAME', 'China Watch');
define('SITE_URL', 'https://chinawatch.com');
define('DEBUG_MODE', true); // Set to false in production

// Paths
define('ROOT_DIR', dirname(__DIR__, 2));
define('VIEWS_DIR', ROOT_DIR . '/core/views');
define('CACHE_DIR', ROOT_DIR . '/core/cache');
