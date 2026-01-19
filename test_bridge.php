<?php
// test_bridge.php
// Verifies that PHP can talk to Python via the SpiderFactory

require_once __DIR__ . '/core/src/bootstrap.php';

use RedPulse\Services\SpiderFactory;

echo "🌉 Testing PHP-Python Bridge...\n";

// 1. Request a Python-based Spider (e.g. 'reddit')
echo "1. Requesting 'reddit' spider from Factory...\n";
$spider = SpiderFactory::create('reddit');

if (get_class($spider) !== 'RedPulse\Services\PythonSpiderWrapper') {
    echo "❌ ERROR: Factory returned wrong class: " . get_class($spider) . "\n";
    exit(1);
}
echo "✅ Factory Success: Received PythonSpiderWrapper\n\n";

// 2. Execute a Crawl (This should trigger bridge_test.py if scrape_reddit.py doesn't exist)
echo "2. Dispatching request to Python...\n";
$url = "https://www.reddit.com/r/China";
$sourceName = "Reddit Test";

$result = $spider->process_url($url, $sourceName);

// 3. Analyze Result
echo "\n3. PHP Received Data:\n";
print_r($result);

if (($result['status'] ?? '') === 'success' && ($result['platform'] ?? '') === 'python_bridge') {
    echo "\n✅ SUCCESS: The Bridge is working! PHP successfully ran Python.\n";
} else {
    echo "\n❌ FAILURE: Unexpected response from Python.\n";
}
