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
define('SERPAPI_KEY', 'bfd194826c78c58b8858ca8f545491b08ed1ed20262c5a2d8ccc77e7c7eb5d5b'); // 250 searches/month
define('PERPLEXITY_API_KEY', 'YOUR_PERPLEXITY_API_KEY_HERE'); // Sonar API

// Cloudinary (Smart Image Cropping with Face Detection)
// Sign up free at: https://cloudinary.com
// Find credentials at: Dashboard > Account Details
define('CLOUDINARY_CLOUD_NAME', 'your_cloud_name');  // e.g., 'dxyz123abc'
define('CLOUDINARY_API_KEY', 'YOUR_CLOUDINARY_API_KEY');  // e.g., '123456789012345'
define('CLOUDINARY_API_SECRET', 'YOUR_CLOUDINARY_API_SECRET');  // e.g., 'AbCdEfGhIjKlMnOpQrStUvWxYz'

// System Configuration
define('SITE_NAME', 'China Watch');
define('SITE_URL', 'https://chinawatch.com');
define('DEBUG_MODE', true); // Set to false in production

// Paths
define('ROOT_DIR', dirname(__DIR__, 2));
define('VIEWS_DIR', ROOT_DIR . '/core/views');
define('CACHE_DIR', ROOT_DIR . '/core/cache');
