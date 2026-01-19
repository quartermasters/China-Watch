<?php
declare(strict_types=1);

// Load Configuration
require_once __DIR__ . '/../../config/env.php';

// Valid PHP Version Check
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    die('Red Pulse requires PHP 8.1 or higher.');
}

// Autoloader (PSR-4 simplified)
spl_autoload_register(function ($class) {
    // Prefix for our namespace
    $prefix = 'RedPulse\\';

    // Base directory for our classes
    $base_dir = __DIR__ . '/';

    // Does the class use the prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Error Handling
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
